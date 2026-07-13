<?php

namespace ExportFile\html\Traits;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use InvalidArgumentException;
use RuntimeException;

/**
 * Helper thao tác DOM HTML: load, thay placeholder, duyệt text node.
 *
 * Class sử dụng trait cần khai báo:
 *   - protected string $originalPath
 *   - public DOMDocument $dom
 *   - public DOMXPath $xpath
 *   - public DOMNodeList $nodes
 */
trait HelperEditHtml
{
    /**
     * Parse chuỗi HTML thành DOMDocument.
     *
     * @throws RuntimeException Parse HTML thất bại
     */
    protected function loadDomDocument(string $html): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        libxml_use_internal_errors(true);

        // Tiền tố encoding giúp libxml parse đúng UTF-8 (tiếng Việt, ký tự đặc biệt).
        // LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD: không bọc thêm <html>/<body> nếu file gốc không có.
        $loaded = $dom->loadHTML(
            '<?xml encoding="utf-8" ?>' . $html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        if ($loaded === false) {
            throw new RuntimeException('File HTML không hợp lệ.');
        }

        return $dom;
    }

    /** Khởi tạo XPath và cache danh sách element node sau khi load DOM. */
    protected function initializeDomQueries(): void
    {
        $this->xpath = new DOMXPath($this->dom);
        $this->nodes = $this->xpath->query('//*');
    }

    /** Refresh danh sách element node sau khi DOM thay đổi nội dung. */
    protected function refreshNodeList(): void
    {
        $this->nodes = $this->xpath->query('//*');
    }

    /**
     * Thay một placeholder cụ thể trong toàn bộ HTML.
     *
     * Duyệt đệ quy cây DOM, chỉ sửa text node (nội dung hiển thị giữa các thẻ),
     * không đụng tới attribute (src, href, class...).
     *
     * Ví dụ:
     *   Text gốc:    "Sách: [!TenSach!] - CXB"
     *   Placeholder: "[!TenSach!]"
     *   Value:       "Toán 1"
     *   Kết quả:     "Sách: Toán 1 - CXB"
     *
     * @throws InvalidArgumentException Placeholder rỗng
     * @throws RuntimeException         Không tìm thấy placeholder nào trong file
     */
    public function replacePlaceholderValue(string $placeholder, mixed $value): void
    {
        if ($placeholder === '') {
            throw new InvalidArgumentException('Placeholder không được rỗng.');
        }

        $replacement = $this->normalizeReplacementValue($value);

        // Bắt đầu duyệt từ root element (<html> hoặc node gốc của file)
        $this->replacePlaceholderInNode($this->dom->documentElement, $placeholder, $replacement);
    }

    /**
     * Chuẩn hóa giá trị thay thế về string an toàn cho DOM text node.
     *
     * null → '', scalar/__toString → cast string, còn lại → ''.
     */
    protected function normalizeReplacementValue(mixed $value): string
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
     * Duyệt đệ quy một node và thay placeholder trong các text node con.
     *
     * Tại sao không str_replace trên cả file HTML?
     * — str_replace toàn file có thể vô tình thay nhầm trong thẻ/attribute.
     *   Chỉ sửa XML_TEXT_NODE giữ nguyên cấu trúc HTML và style.
     *
     * @return bool true nếu tìm thấy và thay ít nhất một chỗ trong nhánh node này
     */
    protected function replacePlaceholderInNode(?DOMNode $node, string $placeholder, string $replacement): bool
    {
        if ($node === null) {
            return false;
        }

        $found = false;

        // Text node: nội dung thuần giữa các thẻ, vd <td>[!TenSach!]</td>
        if ($node->nodeType === XML_TEXT_NODE) {
            $text = $node->nodeValue ?? '';
            if ($text !== '' && str_contains($text, $placeholder)) {
                $node->nodeValue = str_replace($placeholder, $replacement, $text);
                $found = true;
            }

            return $found;
        }

        // Element node: tiếp tục duyệt các con (không sửa trực tiếp element)
        if (!$node->hasChildNodes()) {
            return false;
        }

        foreach ($node->childNodes as $child) {
            if ($this->replacePlaceholderInNode($child, $placeholder, $replacement)) {
                $found = true;
            }
        }

        return $found;
    }

    /**
     * Chuẩn hóa HTML trước khi LibreOffice convert sang DOCX.
     *
     * HTML xuất từ Excel dùng bảng layout với cột cố định theo px (hàng đầu: width:72px, 13px...).
     * LibreOffice import HTML coi đơn vị px gần như point (72dpi), không như browser (96dpi):
     * tổng ~690 "đơn vị" > chiều rộng A4 → Writer thu nhỏ cả bảng còn ~70% trang.
     *
     * Xử lý:
     * 1. @page A4, margin nhỏ, reset body margin
     * 2. Bảng layout width 100% + table-layout:fixed
     * 3. Đổi toàn bộ width/padding px của cột → %
     * 4. Thêm &lt;colgroup&gt; theo tỷ lệ cột
     */
    public function prepareForLibreOfficeDocxExport(
        string $pageMargin = '0.8cm',
        ?string $tableWidth = null
    ): void {
        if ($tableWidth === null) {
            $tableWidth = $this->resolveA4ContentWidth($pageMargin);
        }

        $this->injectLibreOfficeDocxExportStyles($pageMargin, $tableWidth);
        $this->normalizeBodyMarginsForDocxExport();
        $this->normalizeLayoutTableForDocxExport($tableWidth);
        $this->refreshNodeList();
    }

    /**
     * Chiều rộng vùng in A4 sau khi trừ margin trái/phải.
     */
    protected function resolveA4ContentWidth(string $pageMargin): string
    {
        if (preg_match('/([\d.]+)\s*cm/i', $pageMargin, $matches)) {
            $marginCm = (float) $matches[1];
            $contentCm = 21 - ($marginCm * 2);
            if ($contentCm > 0) {
                return rtrim(rtrim(number_format($contentCm, 2, '.', ''), '0'), '.') . 'cm';
            }
        }

        return '100%';
    }

    protected function injectLibreOfficeDocxExportStyles(string $pageMargin, string $tableWidth): void
    {
        $css = implode("\n", [
            '@page {',
            '    size: A4 portrait;',
            '    margin: ' . $pageMargin . ';',
            '}',
            'html, body {',
            '    width: 100% !important;',
            '    margin: 0cm !important;',
            '    padding: 0cm !important;',
            '}',
            'body {',
            '    margin: 0cm !important;',
            '    padding: 0cm !important;',
            '}',
            'table.olm-docx-layout-table {',
            '    width: ' . $tableWidth . ' !important;',
            '    max-width: ' . $tableWidth . ' !important;',
            '    table-layout: fixed !important;',
            '    border-collapse: collapse !important;',
            '}',
            'table.olm-docx-layout-table td,',
            'table.olm-docx-layout-table th {',
            '    margin: 0cm !important;',
            '    box-sizing: border-box !important;',
            '}',
        ]);

        $styleNodes = $this->xpath->query('//style');
        if ($styleNodes->length > 0) {
            $styleElement = $styleNodes->item(0);
            if ($styleElement !== null) {
                $styleElement->nodeValue = rtrim((string) $styleElement->nodeValue) . "\n" . $css;
                return;
            }
        }

        $headNodes = $this->xpath->query('//head');
        if ($headNodes->length === 0) {
            return;
        }

        $head = $headNodes->item(0);
        if ($head === null) {
            return;
        }

        $styleElement = $this->dom->createElement('style', $css);
        $styleElement->setAttribute('type', 'text/css');
        $head->appendChild($styleElement);
    }

    protected function normalizeBodyMarginsForDocxExport(): void
    {
        $bodyNodes = $this->xpath->query('//body');
        if ($bodyNodes->length === 0) {
            return;
        }

        $body = $bodyNodes->item(0);
        if (!($body instanceof DOMElement)) {
            return;
        }

        foreach (['leftMargin', 'topMargin', 'rightMargin', 'bottomMargin', 'marginwidth', 'marginheight'] as $attribute) {
            if ($body->hasAttribute($attribute)) {
                $body->removeAttribute($attribute);
            }
        }

        $this->mergeInlineStyle($body, [
            'margin' => '0',
            'padding' => '0',
        ]);
    }

    /**
     * Chuẩn hóa bảng layout chính: đổi px → % để LibreOffice không scale thu nhỏ.
     */
    protected function normalizeLayoutTableForDocxExport(string $tableWidth): void
    {
        $tableNodes = $this->xpath->query('//body//table');
        if ($tableNodes->length === 0) {
            return;
        }

        $table = $tableNodes->item(0);
        if (!($table instanceof DOMElement)) {
            return;
        }

        $table->setAttribute('width', $tableWidth);
        $table->setAttribute('class', trim($table->getAttribute('class') . ' olm-docx-layout-table'));

        $this->mergeInlineStyle($table, [
            'width' => $tableWidth,
            'max-width' => $tableWidth,
            'table-layout' => 'fixed',
            'border-collapse' => 'collapse',
        ]);

        $tableStyle = (string) $table->getAttribute('style');
        $tableStyle = preg_replace('/\b(height|position)\s*:\s*[^;]+;?/i', '', $tableStyle) ?? $tableStyle;
        $table->setAttribute('style', $tableStyle);

        $columnPixelWidths = $this->extractColumnDefinitionPixelWidths($table);
        if ($columnPixelWidths === []) {
            $this->convertTablePixelLengthsToPercent($table, 690);
            $this->convertStylesheetPixelLengthsToPercent(690);
            return;
        }

        $totalPx = array_sum($columnPixelWidths);
        if ($totalPx <= 0) {
            return;
        }

        $this->applyColgroupPercentWidths($table, $columnPixelWidths, $totalPx);
        $this->convertColumnDefinitionRowToPercent($table, $columnPixelWidths, $totalPx);
        $this->convertTablePixelLengthsToPercent($table, $totalPx);
        $this->convertStylesheetPixelLengthsToPercent($totalPx);
    }

    /**
     * @return list<int>
     */
    protected function extractColumnDefinitionPixelWidths(DOMElement $table): array
    {
        $rows = $table->getElementsByTagName('tr');
        if ($rows->length === 0) {
            return [];
        }

        $firstRow = $rows->item(0);
        if (!($firstRow instanceof DOMElement)) {
            return [];
        }

        $widths = [];
        $hasZeroHeight = false;

        foreach ($firstRow->getElementsByTagName('td') as $cell) {
            if (!($cell instanceof DOMElement)) {
                continue;
            }

            $style = (string) $cell->getAttribute('style');
            if (preg_match('/\bheight\s*:\s*0px\b/i', $style)) {
                $hasZeroHeight = true;
            }

            if (preg_match('/\bwidth\s*:\s*(\d+)px\b/i', $style, $matches)) {
                $widths[] = (int) $matches[1];
                continue;
            }

            $widths[] = 0;
        }

        return $hasZeroHeight && $widths !== [] ? $widths : [];
    }

    /**
     * @param list<int> $columnPixelWidths
     */
    protected function applyColgroupPercentWidths(DOMElement $table, array $columnPixelWidths, int $totalPx): void
    {
        $existing = $table->getElementsByTagName('colgroup');
        while ($existing->length > 0) {
            $node = $existing->item(0);
            if ($node !== null && $node->parentNode !== null) {
                $node->parentNode->removeChild($node);
            }
            $existing = $table->getElementsByTagName('colgroup');
        }

        $colgroup = $this->dom->createElement('colgroup');
        foreach ($columnPixelWidths as $pixelWidth) {
            $col = $this->dom->createElement('col');
            $percent = $this->pixelWidthToPercent($pixelWidth, $totalPx);
            $col->setAttribute('style', 'width:' . $percent . ';');
            $col->setAttribute('width', $percent);
            $colgroup->appendChild($col);
        }

        $firstRow = $table->getElementsByTagName('tr')->item(0);
        if ($firstRow !== null) {
            $table->insertBefore($colgroup, $firstRow);
        } else {
            $table->appendChild($colgroup);
        }
    }

    /**
     * @param list<int> $columnPixelWidths
     */
    protected function convertColumnDefinitionRowToPercent(
        DOMElement $table,
        array $columnPixelWidths,
        int $totalPx
    ): void {
        $firstRow = $table->getElementsByTagName('tr')->item(0);
        if (!($firstRow instanceof DOMElement)) {
            return;
        }

        $index = 0;
        foreach ($firstRow->getElementsByTagName('td') as $cell) {
            if (!($cell instanceof DOMElement)) {
                continue;
            }

            $pixelWidth = $columnPixelWidths[$index] ?? 0;
            $index++;

            $this->mergeInlineStyle($cell, [
                'width' => $this->pixelWidthToPercent($pixelWidth, $totalPx),
                'height' => '0',
                'margin' => '0',
                'padding' => '0',
            ]);
            $cell->removeAttribute('width');
            $cell->removeAttribute('height');
        }
    }

    protected function convertTablePixelLengthsToPercent(DOMElement $table, int $totalPx): void
    {
        if ($totalPx <= 0) {
            return;
        }

        foreach ($table->getElementsByTagName('td') as $cell) {
            if (!($cell instanceof DOMElement)) {
                continue;
            }

            $style = (string) $cell->getAttribute('style');
            if ($style === '') {
                continue;
            }

            $style = $this->replacePixelCssLengthsWithPercent($style, $totalPx);
            $cell->setAttribute('style', $style);
            $cell->removeAttribute('width');
            $cell->removeAttribute('height');
        }

        foreach ($table->getElementsByTagName('th') as $cell) {
            if (!($cell instanceof DOMElement)) {
                continue;
            }

            $style = (string) $cell->getAttribute('style');
            if ($style === '') {
                continue;
            }

            $cell->setAttribute('style', $this->replacePixelCssLengthsWithPercent($style, $totalPx));
        }
    }

    protected function convertStylesheetPixelLengthsToPercent(int $totalPx): void
    {
        if ($totalPx <= 0) {
            return;
        }

        $styleNodes = $this->xpath->query('//style');
        foreach ($styleNodes as $styleNode) {
            if ($styleNode === null) {
                continue;
            }

            $css = (string) $styleNode->nodeValue;
            if ($css === '') {
                continue;
            }

            $styleNode->nodeValue = $this->replacePixelCssLengthsWithPercent($css, $totalPx);
        }
    }

    protected function replacePixelCssLengthsWithPercent(string $style, int $totalPx): string
    {
        return (string) preg_replace_callback(
            '/\b(width|padding-left|padding-right|margin-left|margin-right|text-indent)\s*:\s*(\d+)px\b/i',
            function (array $matches) use ($totalPx): string {
                $property = strtolower($matches[1]);
                $pixels = (int) $matches[2];

                if ($property === 'width' && $pixels >= $totalPx) {
                    return 'width:100%';
                }

                return $property . ':' . $this->pixelWidthToPercent($pixels, $totalPx);
            },
            $style
        );
    }

    protected function pixelWidthToPercent(int $pixels, int $totalPx): string
    {
        if ($totalPx <= 0) {
            return '0%';
        }

        $percent = round(($pixels / $totalPx) * 100, 4);
        if ($percent < 0) {
            $percent = 0;
        }

        return rtrim(rtrim(number_format($percent, 4, '.', ''), '0'), '.') . '%';
    }

    /**
     * @param array<string, string> $rules
     */
    protected function mergeInlineStyle(DOMElement $element, array $rules): void
    {
        $style = (string) $element->getAttribute('style');
        $parsed = [];

        foreach (array_filter(explode(';', $style)) as $chunk) {
            $parts = explode(':', $chunk, 2);
            if (count($parts) !== 2) {
                continue;
            }
            $parsed[strtolower(trim($parts[0]))] = trim($parts[1]);
        }

        foreach ($rules as $property => $value) {
            $parsed[strtolower($property)] = $value;
        }

        $merged = [];
        foreach ($parsed as $property => $value) {
            $merged[] = $property . ':' . $value;
        }

        $element->setAttribute('style', implode(';', $merged) . ';');
    }
}
