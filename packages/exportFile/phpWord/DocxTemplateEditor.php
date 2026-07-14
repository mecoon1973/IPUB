<?php

namespace ExportFile\phpWord;

use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;

/**
 * TemplateProcessor mở rộng: thay chuỗi placeholder tùy ý (vd. [!TenSach!], !TenSach!)
 * mà không bọc macro mặc định ${...}.
 *
 * Làm việc trực tiếp trên XML trong ZIP DOCX nên giữ nguyên DrawingML/VML,
 * textbox, và các phần PhpWord IOFactory::load() thường làm mất.
 */
class DocxTemplateEditor extends TemplateProcessor
{
    /**
     * @param string $documentTemplate
     */
    public function __construct($documentTemplate)
    {
        parent::__construct($documentTemplate);

        $this->tempDocumentMainPart = $this->fixBrokenBangPlaceholders($this->tempDocumentMainPart);
        foreach ($this->tempDocumentHeaders as $index => $headerXml) {
            $this->tempDocumentHeaders[$index] = $this->fixBrokenBangPlaceholders($headerXml);
        }
        foreach ($this->tempDocumentFooters as $index => $footerXml) {
            $this->tempDocumentFooters[$index] = $this->fixBrokenBangPlaceholders($footerXml);
        }
    }

    /**
     * Thay paragraph chứa placeholder bằng HTML đã convert sang OOXML (nhiều &lt;w:p&gt;).
     *
     * Giữ bold/italic/list/xuống dòng/indent margin-left; numbering theo value/start của HTML.
     * Placeholder nên đứng một mình trong một đoạn Word (không chung dòng với text khác).
     */
    public function replaceHtmlBlock(string $search, string $html): void
    {
        if ($search === '') {
            return;
        }

        $searchKeys = $this->resolveSearchKeys($search);
        foreach ($searchKeys as $searchKey) {
            $this->tempDocumentMainPart = $this->replaceHtmlBlockInXml(
                $this->tempDocumentMainPart,
                $searchKey,
                $html
            );
            foreach ($this->tempDocumentHeaders as $index => $headerXml) {
                $this->tempDocumentHeaders[$index] = $this->replaceHtmlBlockInXml(
                    $headerXml,
                    $searchKey,
                    $html
                );
            }
            foreach ($this->tempDocumentFooters as $index => $footerXml) {
                $this->tempDocumentFooters[$index] = $this->replaceHtmlBlockInXml(
                    $footerXml,
                    $searchKey,
                    $html
                );
            }
        }
    }

    /**
     * Thay mọi lần xuất hiện $search bằng $replace trong body/header/footer.
     *
     * Nếu không tìm thấy exact match và $search dạng !Name!, thử không phân biệt hoa/thường
     * (vd. config !TacGia! vs Word !Tacgia!).
     */
    public function replaceLiteral(string $search, string $replace): void
    {
        if ($search === '') {
            return;
        }

        $replace = static::ensureUtf8Encoded($replace);

        // Luôn escape text thuần (& < > " ') khi inject vào OOXML.
        // Settings::isOutputEscapingEnabled() mặc định false trong PhpWord —
        // nếu bỏ qua, chuỗi kiểu "GD&ĐT" làm document.xml invalid và Word không mở được.
        $replace = (new Xml())->escape($replace);

        $replace = $this->replaceCarriageReturns($replace);
        $searchKeys = $this->resolveSearchKeys($search);

        foreach ($searchKeys as $searchKey) {
            $this->tempDocumentHeaders = $this->setValueForPart(
                $searchKey,
                $replace,
                $this->tempDocumentHeaders,
                self::MAXIMUM_REPLACEMENTS_DEFAULT
            );
            $this->tempDocumentMainPart = $this->setValueForPart(
                $searchKey,
                $replace,
                $this->tempDocumentMainPart,
                self::MAXIMUM_REPLACEMENTS_DEFAULT
            );
            $this->tempDocumentFooters = $this->setValueForPart(
                $searchKey,
                $replace,
                $this->tempDocumentFooters,
                self::MAXIMUM_REPLACEMENTS_DEFAULT
            );
        }
    }

    /**
     * Tìm từng lần xuất hiện $searchKey, thay cả &lt;w:p&gt; chứa nó bằng OOXML từ HTML.
     */
    protected function replaceHtmlBlockInXml(string $xml, string $searchKey, string $html): string
    {
        if ($xml === '' || $searchKey === '' || !str_contains($xml, $searchKey)) {
            return $xml;
        }

        $offset = 0;
        while (($pos = strpos($xml, $searchKey, $offset)) !== false) {
            $start = $this->findXmlBlockStartIn($xml, $pos, 'w:p');
            $end = $start >= 0 ? $this->findXmlBlockEndIn($xml, $start, 'w:p') : -1;
            if ($start < 0 || $end < 0) {
                // Không nằm trong paragraph → fallback text thuần (escape)
                $plain = HtmlToDocxXml::looksLikeHtml($html)
                    ? trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'))
                    : $html;
                $escaped = (new Xml())->escape(static::ensureUtf8Encoded($plain));
                $xml = substr($xml, 0, $pos) . $escaped . substr($xml, $pos + strlen($searchKey));
                $offset = $pos + strlen($escaped);
                continue;
            }

            $placeholderPara = substr($xml, $start, $end - $start);
            $defaultRPr = $this->extractFirstRunProperties($placeholderPara);
            $blockXml = HtmlToDocxXml::convert($html, $defaultRPr);

            $xml = substr($xml, 0, $start) . $blockXml . substr($xml, $end);
            $offset = $start + strlen($blockXml);
        }

        return $xml;
    }

    /**
     * @see TemplateProcessor::findXmlBlockStart() — bản làm việc trên chuỗi XML bất kỳ
     */
    protected function findXmlBlockStartIn(string $xml, int $offset, string $blockType): int
    {
        $reverseOffset = (strlen($xml) - $offset) * -1;
        $blockStart = strrpos($xml, '<' . $blockType . ' ', $reverseOffset);
        if (false === $blockStart || strrpos(substr($xml, $blockStart, $offset - $blockStart), '<' . $blockType . '>')) {
            $blockStart = strrpos($xml, '<' . $blockType . '>', $reverseOffset);
        }

        return ($blockStart === false) ? -1 : $blockStart;
    }

    /**
     * @see TemplateProcessor::findXmlBlockEnd()
     */
    protected function findXmlBlockEndIn(string $xml, int $offset, string $blockType): int
    {
        $blockEndStart = strpos($xml, '</' . $blockType . '>', $offset);

        return ($blockEndStart === false) ? -1 : $blockEndStart + 3 + strlen($blockType);
    }

    /**
     * Lấy &lt;w:rPr&gt; từ run text chứa placeholder (không lấy rPr trong w:pPr —
     * pPr thường có w:b w:val="0" và sẽ đè mất bold/italic của HTML).
     */
    protected function extractFirstRunProperties(string $paragraphXml): string
    {
        if (preg_match('/<w:r\b[^>]*>.*?<w:rPr\b[^>]*>.*?<\/w:rPr>/s', $paragraphXml, $runMatch) === 1
            && preg_match('/<w:rPr\b[^>]*>.*?<\/w:rPr>/s', $runMatch[0], $match) === 1
        ) {
            return $match[0];
        }

        if (preg_match('/<w:r\b[^>]*>.*?<w:rPr\b[^>]*\/>/s', $paragraphXml, $runMatch) === 1
            && preg_match('/<w:rPr\b[^>]*\/>/', $runMatch[0], $match) === 1
        ) {
            return $match[0];
        }

        return '';
    }

    /**
     * Word thường tách placeholder !Name! thành nhiều <w:t> (vd. "!" + "Name" + "!").
     * Nối lại thành một chuỗi liền trước khi replace.
     */
    protected function fixBrokenBangPlaceholders(string $xml): string
    {
        if ($xml === '' || !str_contains($xml, '!')) {
            return $xml;
        }

        $fixed = preg_replace_callback(
            '/!(?:(?:(?![!<>])[^<])|<[^>]+>)+?!/u',
            static function (array $match): string {
                $stripped = preg_replace('/<[^>]+>/', '', $match[0]);
                if (!is_string($stripped) || !preg_match('/^![A-Za-z0-9_]+!$/', $stripped)) {
                    return $match[0];
                }

                return $stripped;
            },
            $xml
        );

        return is_string($fixed) ? $fixed : $xml;
    }

    /**
     * Nhân bản hàng bảng (<w:tr>) chứa $anchorPlaceholder.
     *
     * Tương đương Excel duplicateRowCellsBelow: hàng gốc + $duplicateCount bản sao.
     * Placeholder trong mỗi hàng được đánh chỉ số: !a! → !a#1!, !a#2!, …
     * (tránh đụng substring khi replace lần lượt).
     *
     * @param list<string> $placeholdersToIndex Placeholder cần đánh chỉ số (thường = columnKeys)
     */
    public function duplicateTableRowBelow(string $anchorPlaceholder, int $duplicateCount, array $placeholdersToIndex = []): void
    {
        if ($duplicateCount < 0) {
            throw new RuntimeException('duplicateCount không được âm.');
        }

        $totalRows = $duplicateCount + 1;
        $anchor = $this->resolveSearchKeys($anchorPlaceholder)[0] ?? $anchorPlaceholder;

        $tagPos = strpos($this->tempDocumentMainPart, $anchor);
        if ($tagPos === false) {
            throw new RuntimeException(
                'Không tìm thấy placeholder neo hàng loop: ' . $anchorPlaceholder
            );
        }

        try {
            $rowStart = $this->findRowStart($tagPos);
            $rowEnd = $this->findRowEnd($tagPos);
        } catch (PhpWordException $exception) {
            throw new RuntimeException(
                'Placeholder loop phải nằm trong hàng bảng (<w:tr>): ' . $anchorPlaceholder,
                0,
                $exception
            );
        }

        $xmlRow = $this->getSlice($rowStart, $rowEnd);
        $placeholders = $placeholdersToIndex !== []
            ? $placeholdersToIndex
            : [$anchorPlaceholder];

        $clonedRows = $this->indexClonedBangPlaceholders($totalRows, $xmlRow, $placeholders);

        $this->tempDocumentMainPart =
            $this->getSlice(0, $rowStart)
            . implode('', $clonedRows)
            . $this->getSlice($rowEnd);
    }

    /**
     * Điền giá trị vào các hàng đã nhân bản (placeholder dạng !Name#1!, !Name#2!, …).
     *
     * @param list<string>                    $columnKeys       Key map_replate (vd. ["!a!", "!b!"])
     * @param array<int, array<int, mixed>>   $rowValuesMatrix  Hàng × cột, thứ tự khớp $columnKeys
     */
    public function fillDuplicatedRowValues(array $columnKeys, array $rowValuesMatrix): void
    {
        if ($columnKeys === [] || $rowValuesMatrix === []) {
            return;
        }

        foreach ($rowValuesMatrix as $rowIndex => $rowValues) {
            if (!is_array($rowValues)) {
                continue;
            }

            $rowNumber = $rowIndex + 1;

            foreach ($columnKeys as $colIndex => $placeholder) {
                if (!is_string($placeholder) || $placeholder === '') {
                    continue;
                }

                $indexed = $this->buildIndexedBangPlaceholder($placeholder, $rowNumber);
                $value = $rowValues[$colIndex] ?? '';
                $this->replaceLiteral($indexed, $this->stringifyLoopValue($value));
            }
        }
    }

    /**
     * Clone hàng template và điền luôn (gộp duplicate + fill).
     *
     * @param list<string>                  $columnKeys
     * @param array<int, array<int, mixed>> $rowValuesMatrix
     */
    public function applyLoopRows(array $columnKeys, array $rowValuesMatrix): void
    {
        if ($columnKeys === [] || $rowValuesMatrix === []) {
            return;
        }

        $anchor = $columnKeys[0];
        $this->duplicateTableRowBelow($anchor, count($rowValuesMatrix) - 1, $columnKeys);
        $this->fillDuplicatedRowValues($columnKeys, $rowValuesMatrix);
    }

    /**
     * Đánh chỉ số placeholder bang trong mỗi bản sao hàng: !a! → !a#1!, !a#2!, …
     *
     * @param list<string> $placeholders
     *
     * @return list<string>
     */
    protected function indexClonedBangPlaceholders(int $count, string $xmlBlock, array $placeholders): array
    {
        $resolvedPlaceholders = [];
        foreach ($placeholders as $placeholder) {
            if (!is_string($placeholder) || $placeholder === '') {
                continue;
            }
            $resolvedPlaceholders[] = $this->resolveSearchKeys($placeholder)[0] ?? $placeholder;
        }
        $resolvedPlaceholders = array_values(array_unique($resolvedPlaceholders));

        // Placeholder dài hơn trước để tránh !ab! nuốt !a!
        usort($resolvedPlaceholders, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        $results = [];
        for ($i = 1; $i <= $count; ++$i) {
            $rowXml = $xmlBlock;
            foreach ($resolvedPlaceholders as $placeholder) {
                $indexed = $this->buildIndexedBangPlaceholder($placeholder, $i);
                $rowXml = str_replace($placeholder, $indexed, $rowXml);
            }
            $results[] = $rowXml;
        }

        return $results;
    }

    /**
     * !Name! → !Name#2! ; [!Name!] → [!Name#2!]
     */
    protected function buildIndexedBangPlaceholder(string $placeholder, int $rowNumber): string
    {
        if (preg_match('/^\[!(.+?)!\]$/', $placeholder, $match) === 1) {
            return '[!' . $match[1] . '#' . $rowNumber . '!]';
        }

        if (preg_match('/^!(.+?)!$/', $placeholder, $match) === 1) {
            return '!' . $match[1] . '#' . $rowNumber . '!';
        }

        return $placeholder . '#' . $rowNumber;
    }

    protected function stringifyLoopValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
            return (string) $value;
        }

        return '';
    }

    /**
     * Chọn chuỗi placeholder thực tế sẽ đưa vào setValueForPart.
     *
     * Thứ tự:
     * 1. Có exact match trong doc → giữ nguyên $search
     * 2. Không phải dạng !Name! / [!Name!] → không fallback case, trả $search
     * 3. Có biến thể khác hoa/thường trong doc → dùng biến thể đó (Word giữ casing gốc)
     * 4. Không tìm thấy → vẫn trả $search (replace sẽ no-op)
     *
     * @return list<string>
     */
    protected function resolveSearchKeys(string $search): array
    {
        if ($this->documentContains($search)) {
            return [$search];
        }

        // Chỉ fallback case-insensitive cho placeholder bang chuẩn (kể cả đã đánh chỉ số !a#1!)
        if (
            !preg_match('/^![A-Za-z0-9_]+(?:#\d+)?!$/', $search)
            && !preg_match('/^\[![A-Za-z0-9_]+(?:#\d+)?!\]$/', $search)
        ) {
            return [$search];
        }

        $caseVariant = $this->findCaseInsensitivePlaceholder($search);
        if ($caseVariant !== null && $caseVariant !== $search) {
            return [$caseVariant];
        }

        return [$search];
    }

    /**
     * Kiểm tra $needle xuất hiện exact (phân biệt hoa/thường) trong body, header, footer.
     */
    protected function documentContains(string $needle): bool
    {
        if (str_contains($this->tempDocumentMainPart, $needle)) {
            return true;
        }

        foreach ($this->tempDocumentHeaders as $headerXml) {
            if (str_contains($headerXml, $needle)) {
                return true;
            }
        }

        foreach ($this->tempDocumentFooters as $footerXml) {
            if (str_contains($footerXml, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tìm lần xuất hiện đầu tiên của $search trong doc, không phân biệt hoa/thường.
     *
     * Ví dụ: config "!TacGia!" nhưng Word lưu "!Tacgia!" → trả "!Tacgia!"
     * để setValueForPart match đúng chuỗi trong XML.
     *
     * @return string|null Chuỗi đúng casing trong XML, hoặc null nếu không có
     */
    protected function findCaseInsensitivePlaceholder(string $search): ?string
    {
        $parts = [$this->tempDocumentMainPart];
        foreach ($this->tempDocumentHeaders as $headerXml) {
            $parts[] = $headerXml;
        }
        foreach ($this->tempDocumentFooters as $footerXml) {
            $parts[] = $footerXml;
        }

        // /i = ignore case; trả về đúng text như trong XML ($match[0])
        $pattern = '/' . preg_quote($search, '/') . '/iu';
        foreach ($parts as $xml) {
            if (preg_match($pattern, $xml, $match) === 1) {
                return $match[0];
            }
        }

        return null;
    }
}
