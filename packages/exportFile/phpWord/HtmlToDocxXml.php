<?php

namespace ExportFile\phpWord;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Shared\Converter;

/**
 * Chuyển HTML fragment → WordProcessingML (&lt;w:p&gt;…) để inject vào DOCX template.
 *
 * Pipeline:
 *   HTML (CKEditor/DB)
 *     → HtmlToDocxXml::convert()
 *     → chuỗi OOXML gồm nhiều &lt;w:p&gt;
 *     → DocxTemplateEditor::replaceHtmlBlock() thay cả đoạn chứa !Placeholder!
 *
 * Fidelity hỗ trợ:
 * - bold/italic/underline (&lt;strong&gt;/&lt;b&gt;, &lt;em&gt;/&lt;i&gt;, &lt;u&gt; + CSS tương ứng)
 * - xuống dòng (&lt;p&gt;, &lt;br&gt;)
 * - list (&lt;ul&gt;/&lt;ol&gt;/&lt;li&gt;) — numbering literal "1. " / "• "
 * - indent margin-left (pt/px/cm → twip)
 *
 * Vì sao numbering literal (không dùng Word auto-numId)?
 * Inject &lt;w:numPr&gt; vào template sẵn đòi hỏi đồng bộ numbering.xml;
 * dễ lệch/mất số. Text "1. "/"2. " luôn khớp HTML value/start.
 *
 * Giới hạn cố ý: chưa render table/img; heading = paragraph thường;
 * CSS phức tạp (color, font-size trên span) chưa map đầy đủ.
 */
final class HtmlToDocxXml
{
    /** Indent mỗi cấp list (360 twip ≈ 0.25 inch) */
    private const LIST_INDENT_TWIP = 360;

    /** Ký tự bullet cho &lt;ul&gt; */
    private const BULLET = '•';

    /**
     * Font mặc định khi placeholder không có &lt;w:rPr&gt; để kế thừa.
     * size tính bằng half-points (24 = 12pt).
     *
     * @var array{name: string, size: int}
     */
    private array $defaultFont = [
        'name' => 'Times New Roman',
        'size' => 24,
    ];

    /**
     * &lt;w:rPr&gt;…&lt;/w:rPr&gt; lấy từ paragraph placeholder trong template
     * (font name/size của chỗ đặt !Name!) — null/rỗng thì dùng $defaultFont.
     */
    private string $defaultRPrXml = '';

    private Xml $escaper;

    /**
     * @param string|null $defaultRPrXml XML &lt;w:rPr&gt; kế thừa từ đoạn placeholder (optional)
     */
    public function __construct(?string $defaultRPrXml = null)
    {
        $this->escaper = new Xml();
        if (is_string($defaultRPrXml) && $defaultRPrXml !== '') {
            $this->defaultRPrXml = $defaultRPrXml;
        }
    }

    /**
     * Detect string có vẻ là HTML (để EditWord tách plain vs html khi map content_edit).
     */
    public static function looksLikeHtml(string $value): bool
    {
        return (bool) preg_match(
            '/<\/?(?:p|div|ul|ol|li|br|strong|em|b|i|u|span|h[1-6]|table|tr|td|th)\b/i',
            $value
        );
    }

    /**
     * Entry point tiện dụng: HTML → OOXML string.
     *
     * @param string      $html           Fragment HTML (không cần &lt;html&gt;/&lt;body&gt;)
     * @param string|null $defaultRPrXml  &lt;w:rPr&gt; kế thừa từ placeholder
     */
    public static function convert(string $html, ?string $defaultRPrXml = null): string
    {
        return (new self($defaultRPrXml))->toXml($html);
    }

    /**
     * Parse HTML → duyệt DOM → nối các &lt;w:p&gt;.
     *
     * Bọc &lt;body&gt; vì fragment có nhiều root (&lt;p&gt;+&lt;ul&gt;+…).
     * LIBXML_HTML_NODEFDTD: không thêm &lt;!DOCTYPE&gt;.
     */
    public function toXml(string $html): string
    {
        // &uacute; / &nbsp; … → ký tự thật trước khi parse DOM
        $html = html_entity_decode(trim($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($html === '') {
            return $this->emptyParagraph();
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><body>' . $html . '</body>',
            LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body instanceof DOMElement) {
            return $this->emptyParagraph();
        }

        $parts = [];
        foreach ($body->childNodes as $child) {
            $chunk = $this->renderBlockNode($child, 0);
            // Bỏ chunk rỗng (whitespace text / &lt;p&gt;&nbsp;&lt;/p&gt; đã skip)
            if ($chunk !== '') {
                $parts[] = $chunk;
            }
        }

        $xml = implode('', $parts);

        return $xml !== '' ? $xml : $this->emptyParagraph();
    }

    /**
     * Dispatch 1 DOM node ở cấp block → OOXML (có thể nhiều &lt;w:p&gt;).
     *
     * @param int $listLevel Cấp list hiện tại (0 = ngoài list); ảnh hưởng indent
     */
    protected function renderBlockNode(DOMNode $node, int $listLevel): string
    {
        // Pretty-print HTML thường có text "\n\t" giữa &lt;/p&gt; và &lt;p&gt;.
        // Nếu không trim+skip sẽ tạo &lt;w:p&gt; trống → xuống dòng thừa hàng loạt.
        if ($node instanceof DOMText) {
            $text = trim($this->normalizeText($node->textContent));
            if ($text === '') {
                return '';
            }

            return $this->paragraphFromRuns([$this->run($text, [])], 0);
        }

        if (!$node instanceof DOMElement) {
            return '';
        }

        $tag = strtolower($node->tagName);

        return match ($tag) {
            'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' => $this->renderParagraph($node, $listLevel),
            'div', 'section', 'article' => $this->renderContainer($node, $listLevel),
            'ul' => $this->renderUnorderedList($node, $listLevel),
            'ol' => $this->renderOrderedList($node, $listLevel),
            // &lt;br&gt; đứng riêng = 1 dòng trống có chủ đích (khác &lt;p&gt;&nbsp;&lt;/p&gt; bị bỏ)
            'br' => $this->emptyParagraph(),
            'li' => $this->renderListItem($node, $listLevel, 'ul', null),
            // Inline lạc ở top-level → bọc 1 paragraph
            default => $this->isInlineTag($tag)
                ? $this->paragraphFromRuns($this->collectRuns($node, []), $listLevel * self::LIST_INDENT_TWIP)
                : $this->renderContainer($node, $listLevel),
        };
    }

    /** Đệ quy con của container (div/section) — không tự tạo paragraph bọc. */
    protected function renderContainer(DOMElement $node, int $listLevel): string
    {
        $xml = '';
        foreach ($node->childNodes as $child) {
            $xml .= $this->renderBlockNode($child, $listLevel);
        }

        return $xml;
    }

    /**
     * &lt;p&gt; / heading → một &lt;w:p&gt;.
     *
     * Bỏ đoạn rỗng (&lt;p&gt;&lt;/p&gt;, &lt;p&gt;&nbsp;&lt;/p&gt;) để tránh dòng thừa.
     * Indent = margin-left CSS + indent theo listLevel.
     * Style CSS trên chính thẻ &lt;p&gt; (font-weight/…) được cộng vào mọi run con.
     */
    protected function renderParagraph(DOMElement $node, int $listLevel): string
    {
        $indent = $this->paragraphIndentTwip($node) + ($listLevel * self::LIST_INDENT_TWIP);
        $baseStyle = $this->mergeInlineStyle([], strtolower($node->tagName), $node->getAttribute('style'));
        $runs = $this->trimEdgeWhitespaceRuns($this->collectRuns($node, $baseStyle));

        if ($runs === [] || $this->runsAreVisuallyEmpty($runs)) {
            return '';
        }

        return $this->paragraphFromRuns($runs, $indent);
    }

    /** &lt;ul&gt; → từng &lt;li&gt; thành paragraph với prefix "• ". */
    protected function renderUnorderedList(DOMElement $node, int $listLevel): string
    {
        $xml = '';
        foreach ($node->childNodes as $child) {
            if (!$child instanceof DOMElement || strtolower($child->tagName) !== 'li') {
                continue;
            }
            $xml .= $this->renderListItem($child, $listLevel, 'ul', null);
        }

        return $xml;
    }

    /**
     * &lt;ol&gt; → từng &lt;li&gt; với số literal.
     *
     * Hỗ trợ:
     * - start="N" trên &lt;ol&gt;
     * - value="N" trên &lt;li&gt; (HTML CKEditor hay ngắt ol rồi tiếp value="2")
     */
    protected function renderOrderedList(DOMElement $node, int $listLevel): string
    {
        $counter = 1;
        if ($node->hasAttribute('start') && is_numeric($node->getAttribute('start'))) {
            $counter = max(1, (int) $node->getAttribute('start'));
        }

        $xml = '';
        foreach ($node->childNodes as $child) {
            if (!$child instanceof DOMElement || strtolower($child->tagName) !== 'li') {
                continue;
            }

            if ($child->hasAttribute('value') && is_numeric($child->getAttribute('value'))) {
                $counter = max(1, (int) $child->getAttribute('value'));
            }

            $xml .= $this->renderListItem($child, $listLevel, 'ol', $counter);
            ++$counter;
        }

        return $xml;
    }

    /**
     * Một &lt;li&gt; → 1 &lt;w:p&gt; (prefix + nội dung) + có thể thêm block list lồng / &lt;p&gt; sau.
     *
     * Cấu trúc:
     * - Prefix "1. " hoặc "• " luôn là run đầu (không bold theo nội dung)
     * - &lt;p&gt; đầu trong li gộp cùng dòng với số
     * - &lt;p&gt; tiếp theo → paragraph riêng ($blockAfter)
     * - &lt;ul&gt;/&lt;ol&gt; con → render ở listLevel+1
     *
     * @param string   $listType "ol"|"ul"
     * @param int|null $number   Số hiện tại (chỉ dùng khi ol)
     */
    protected function renderListItem(
        DOMElement $li,
        int $listLevel,
        string $listType,
        ?int $number
    ): string {
        $indent = (($listLevel + 1) * self::LIST_INDENT_TWIP) + $this->paragraphIndentTwip($li);
        $prefix = $listType === 'ol'
            ? ((string) ($number ?? 1) . '. ')
            : (self::BULLET . ' ');

        $runs = [];
        $runs[] = $this->run($prefix, []);

        $blockAfter = '';
        foreach ($li->childNodes as $child) {
            if ($child instanceof DOMElement) {
                $childTag = strtolower($child->tagName);
                if (in_array($childTag, ['ul', 'ol'], true)) {
                    $blockAfter .= $this->renderBlockNode($child, $listLevel + 1);
                    continue;
                }
                if ($childTag === 'p') {
                    // count===1 → mới có prefix, chưa có nội dung → gộp inline
                    if (count($runs) === 1) {
                        $pStyle = $this->mergeInlineStyle([], $childTag, $child->getAttribute('style'));
                        foreach ($this->trimEdgeWhitespaceRuns($this->collectRuns($child, $pStyle)) as $run) {
                            $runs[] = $run;
                        }
                    } else {
                        $blockAfter .= $this->renderParagraph($child, $listLevel + 1);
                    }
                    continue;
                }
            }

            // Bỏ "\n\t" pretty-print bên trong &lt;li&gt;
            if ($child instanceof DOMText && trim($this->normalizeText($child->textContent)) === '') {
                continue;
            }

            foreach ($this->collectRunsFromNode($child, []) as $run) {
                $runs[] = $run;
            }
        }

        // Cắt whitespace đầu/cuối phần nội dung (giữ nguyên prefix)
        $contentRuns = array_slice($runs, 1);
        $contentRuns = $this->trimEdgeWhitespaceRuns($contentRuns);
        $runs = array_merge([$this->run($prefix, [])], $contentRuns);

        return $this->paragraphFromRuns($runs, $indent) . $blockAfter;
    }

    /**
     * Duyệt mọi con của element → danh sách &lt;w:r&gt; XML (style cộng dồn từ cha).
     *
     * @param array{bold?: bool, italic?: bool, underline?: bool} $style
     *
     * @return list<string>
     */
    protected function collectRuns(DOMElement $node, array $style): array
    {
        $runs = [];
        foreach ($node->childNodes as $child) {
            foreach ($this->collectRunsFromNode($child, $style) as $run) {
                $runs[] = $run;
            }
        }

        return $runs;
    }

    /**
     * 1 node → 0..n run XML.
     *
     * - Text → 1 run với $style hiện tại
     * - &lt;br&gt; → &lt;w:br/&gt; trong run (xuống dòng trong cùng paragraph)
     * - Block (p/ul/…) trong context inline → bỏ (xử lý ở tầng block)
     * - Inline tag → merge style rồi đệ quy con
     *
     * @param array{bold?: bool, italic?: bool, underline?: bool} $style
     *
     * @return list<string>
     */
    protected function collectRunsFromNode(DOMNode $node, array $style): array
    {
        if ($node instanceof DOMText) {
            $text = $this->normalizeText($node->textContent);
            if ($text === '') {
                return [];
            }

            return [$this->run($text, $style)];
        }

        if (!$node instanceof DOMElement) {
            return [];
        }

        $tag = strtolower($node->tagName);
        if ($tag === 'br') {
            return ['<w:r>' . $this->resolveRPr($style) . '<w:br/></w:r>'];
        }

        // Block lẫn trong inline collector — bỏ, tránh double-render
        if (in_array($tag, ['ul', 'ol', 'p', 'div', 'table', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true)) {
            return [];
        }

        $merged = $this->mergeInlineStyle($style, $tag, $node->getAttribute('style'));

        return $this->collectRuns($node, $merged);
    }

    /**
     * Cộng dồn format từ tên thẻ + CSS inline vào style cha.
     *
     * @param array{bold?: bool, italic?: bool, underline?: bool} $style
     *
     * @return array{bold?: bool, italic?: bool, underline?: bool}
     */
    protected function mergeInlineStyle(array $style, string $tag, string $css): array
    {
        $merged = $style;

        if (in_array($tag, ['strong', 'b'], true)) {
            $merged['bold'] = true;
        }
        if (in_array($tag, ['em', 'i'], true)) {
            $merged['italic'] = true;
        }
        if ($tag === 'u') {
            $merged['underline'] = true;
        }

        foreach ($this->parseCssDeclarations($css) as $key => $value) {
            $key = strtolower(trim($key));
            $value = strtolower(trim($value));
            if ($key === 'font-weight' && ($value === 'bold' || (is_numeric($value) && (int) $value >= 600))) {
                $merged['bold'] = true;
            }
            if ($key === 'font-style' && ($value === 'italic' || $value === 'oblique')) {
                $merged['italic'] = true;
            }
            if ($key === 'text-decoration' && str_contains($value, 'underline')) {
                $merged['underline'] = true;
            }
        }

        return $merged;
    }

    /**
     * Parse attribute style="a: b; c: d" → ['a' => 'b', 'c' => 'd'].
     *
     * @return array<string, string>
     */
    protected function parseCssDeclarations(string $style): array
    {
        $result = [];
        foreach (explode(';', $style) as $chunk) {
            $chunk = trim($chunk);
            if ($chunk === '' || !str_contains($chunk, ':')) {
                continue;
            }
            [$key, $value] = array_map('trim', explode(':', $chunk, 2));
            if ($key !== '') {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * margin-left CSS → twip cho w:ind.
     * Ví dụ: 36pt → 720 twip (1pt = 20 twip).
     */
    protected function paragraphIndentTwip(DOMElement $node): int
    {
        $css = $this->parseCssDeclarations($node->getAttribute('style'));
        if (!isset($css['margin-left'])) {
            return 0;
        }

        $twip = Converter::cssToTwip($css['margin-left']);

        return (int) round($twip);
    }

    /**
     * Ghép runs thành một &lt;w:p&gt; hoàn chỉnh.
     *
     * spacing before/after=0: Word mặc định có "Space After" ~200 twip,
     * khiến mỗi &lt;p&gt; HTML nhìn như xuống dòng đôi. line=240 = single spacing (240 twip).
     *
     * @param list<string> $runs Chuỗi XML &lt;w:r&gt;… đã dựng sẵn
     */
    protected function paragraphFromRuns(array $runs, int $indentTwip): string
    {
        $pPrParts = ['<w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="auto"/>'];
        if ($indentTwip > 0) {
            $pPrParts[] = '<w:ind w:left="' . $indentTwip . '"/>';
        }
        $pPr = '<w:pPr>' . implode('', $pPrParts) . '</w:pPr>';

        if ($runs === []) {
            $runs = [$this->run('', [])];
        }

        return '<w:p>' . $pPr . implode('', $runs) . '</w:p>';
    }

    /**
     * Cắt các run chỉ chứa whitespace ở đầu/cuối danh sách.
     * Giữ space ở giữa (vd. giữa &lt;/strong&gt; và &lt;em&gt;).
     *
     * @param list<string> $runs
     *
     * @return list<string>
     */
    protected function trimEdgeWhitespaceRuns(array $runs): array
    {
        while ($runs !== [] && $this->isWhitespaceOnlyRun($runs[0])) {
            array_shift($runs);
        }
        while ($runs !== [] && $this->isWhitespaceOnlyRun($runs[array_key_last($runs)])) {
            array_pop($runs);
        }

        return array_values($runs);
    }

    /**
     * Run XML có "nhìn thấy" nội dung không?
     * &lt;w:br/&gt; coi là có nội dung (xuống dòng chủ đích).
     */
    protected function isWhitespaceOnlyRun(string $runXml): bool
    {
        if (str_contains($runXml, '<w:br')) {
            return false;
        }

        if (preg_match('/<w:t\b[^>]*>(.*?)<\/w:t>/s', $runXml, $match) !== 1) {
            return true;
        }

        return trim(html_entity_decode($match[1], ENT_QUOTES | ENT_XML1, 'UTF-8')) === '';
    }

    /**
     * Tất cả runs đều whitespace → paragraph rỗng về mặt hiển thị.
     *
     * @param list<string> $runs
     */
    protected function runsAreVisuallyEmpty(array $runs): bool
    {
        foreach ($runs as $run) {
            if (!$this->isWhitespaceOnlyRun($run)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Một text run OOXML: &lt;w:r&gt;&lt;w:rPr/&gt;&lt;w:t&gt;…&lt;/w:t&gt;&lt;/w:r&gt;.
     * xml:space="preserve" để Word không nuốt space đầu/cuối.
     *
     * @param array{bold?: bool, italic?: bool, underline?: bool} $style
     */
    protected function run(string $text, array $style): string
    {
        $rPr = $this->resolveRPr($style);
        $escaped = $this->escaper->escape($text);

        return '<w:r>' . $rPr . '<w:t xml:space="preserve">' . $escaped . '</w:t></w:r>';
    }

    /**
     * Dựng &lt;w:rPr&gt;: font từ placeholder (nếu có) + chồng bold/italic/underline của HTML.
     *
     * Khi $style rỗng và có defaultRPrXml → trả nguyên rPr placeholder (không đụng format).
     * Khi có style HTML: gỡ hết b/i/u (kể cả w:val="0" / dạng &lt;w:b&gt;&lt;/w:b&gt;) rồi gắn lại —
     * tránh Word lấy property cũ phía trước (val=0) và bỏ qua &lt;w:b/&gt; phía sau.
     *
     * @param array{bold?: bool, italic?: bool, underline?: bool} $style
     */
    protected function resolveRPr(array $style): string
    {
        if ($this->defaultRPrXml !== '' && $style === []) {
            return $this->defaultRPrXml;
        }

        $parts = [];

        if ($this->defaultRPrXml !== '') {
            $base = $this->defaultRPrXml;
            $base = preg_replace('/<\/?w:rPr\b[^>]*>/', '', $base) ?? '';
            $base = $this->stripRunEmphasisProperties($base);
            $parts[] = $base;
        } else {
            $parts[] = '<w:rFonts w:ascii="' . $this->defaultFont['name']
                . '" w:hAnsi="' . $this->defaultFont['name'] . '"/>';
            $parts[] = '<w:sz w:val="' . $this->defaultFont['size'] . '"/>';
            $parts[] = '<w:szCs w:val="' . $this->defaultFont['size'] . '"/>';
        }

        if (!empty($style['bold'])) {
            $parts[] = '<w:b w:val="true"/><w:bCs w:val="true"/>';
        }
        if (!empty($style['italic'])) {
            $parts[] = '<w:i w:val="true"/><w:iCs w:val="true"/>';
        }
        if (!empty($style['underline'])) {
            $parts[] = '<w:u w:val="single"/>';
        }

        return '<w:rPr>' . implode('', $parts) . '</w:rPr>';
    }

    /**
     * Gỡ mọi dạng bold/italic/underline trong rPr nội dung (self-closing + open/close + bCs/iCs).
     */
    protected function stripRunEmphasisProperties(string $rPrInnerXml): string
    {
        $patterns = [
            // <w:b .../> | <w:bCs .../> | <w:i .../> | <w:iCs .../> | <w:u .../>
            '/<w:(?:bCs|b|iCs|i|u)\b[^>]*\/>/',
            // <w:b>...</w:b> (đúng cặp tag)
            '/<w:(bCs|b|iCs|i|u)\b[^>]*>.*?<\/w:\1>/',
        ];

        foreach ($patterns as $pattern) {
            $rPrInnerXml = preg_replace($pattern, '', $rPrInnerXml) ?? $rPrInnerXml;
        }

        return $rPrInnerXml;
    }

    /**
     * Chuẩn hóa text node:
     * - nbsp → space thường
     * - mọi khoảng trắng liên tiếp (newline/tab pretty-print) → 1 space
     */
    protected function normalizeText(string $text): string
    {
        $text = str_replace(["\xc2\xa0", '&nbsp;', "\u{00A0}"], ' ', $text);
        $collapsed = preg_replace('/\s+/u', ' ', $text);

        return is_string($collapsed) ? $collapsed : $text;
    }

    /** Paragraph trống — dùng khi HTML rỗng hoặc &lt;br&gt; đứng riêng. */
    protected function emptyParagraph(): string
    {
        return $this->paragraphFromRuns([$this->run('', [])], 0);
    }

    /** Thẻ inline được phép merge style khi đệ quy. */
    protected function isInlineTag(string $tag): bool
    {
        return in_array($tag, ['span', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'a', 'sub', 'sup'], true);
    }
}
