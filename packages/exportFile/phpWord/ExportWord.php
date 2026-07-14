<?php

namespace ExportFile\phpWord;

use DOMElement;
use ExportFile\ReadDOMDocument;
use ExportFile\phpWord\Traits\HelperStyleWord;
use ExportFile\phpWord\Traits\HelperRenderWord;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\NumberFormat;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Cell as CellStyle;

/** Xuất file Word từ HTML */
class ExportWord {
    use HelperStyleWord, HelperRenderWord;
    protected PhpWord $phpWord;
    protected Section $section;
    protected TextRun $textRun;

    protected string $html;
    protected ReadDOMDocument $readDom;

    /** @var array<string, mixed> */
    protected array $defaultFontStyle = [
        'name' => 'Times New Roman',
        'size' => 12,
    ];

    public function __construct(string $html = '')
    {
        $this->phpWord = new PhpWord();
        $this->createSection();
        $this->html = $html;
        $this->settingDefaultPhpWord();
        $this->addFooter();
        $this->readDom = new ReadDOMDocument($this->html);
        $this->createTextRun();
    }

    /** lấy path file image từ src */
    public function getImageFilePath(string $src): string
    {
        return $this->readDom->imageNodes[$src] ?? '';
    }

    public function createSection(): void
    {
        if(isset($this->section)){
            if($this->section->countElements() == 0){
                return;
            }
            if($this->section->countElements() == 1){
                // sử lý trường hợp đặc biệt khi các thẻ có logic bắt buộc phải tạo section mới
                $textRun = $this->section->getElement(0);
                if($textRun instanceof TextRun && $textRun->countElements() == 0){
                    return;
                }
            }
        }
        $this->section = $this->phpWord->addSection([
            'pageSizeW' => 11906,
            'pageSizeH' => 16838,
            'marginTop' => 900,
            'marginBottom' => 900,
            'marginLeft' => 900,
            'marginRight' => 900,
            'headerHeight' => 450,
            'footerHeight' => 450,
        ]);
    }

    /** @param array<string, mixed> $pStyle */
    public function createTextRun(array $pStyle = []): void
    {
        $this->textRun = $this->section->addTextRun($pStyle);
    }

    public function settingDefaultPhpWord(): void
    {
        $this->phpWord->setDefaultFontName('Times New Roman');
        $this->phpWord->setDefaultFontSize(12);

        foreach(self::$MAP_STYLE_HEADING as $depth => $style){
            $this->addTitleStyle($depth, $style);
        }

        /** style riêng để giống trên CkEiditor */
        $this->phpWord->addNumberingStyle(
            self::$MAP_TAGNAME_NUMBERING["ol"],
            [
                'type' => 'multilevel',
                'levels' => [
                    ['format' => NumberFormat::DECIMAL,      'text' => '%1.',           'alignment' => 'left', 'tabPos' => 360,  'left' => 360,  'hanging' => 360],
                    ['format' => NumberFormat::LOWER_LETTER, 'text' => '%1.%2.',        'alignment' => 'left', 'tabPos' => 720, 'left' => 720, 'hanging' => 360],
                    ['format' => NumberFormat::LOWER_ROMAN,  'text' => '%1.%2.%3.',     'alignment' => 'left', 'tabPos' => 1080, 'left' => 1080, 'hanging' => 360],
                    ['format' => NumberFormat::UPPER_LETTER, 'text' => '%1.%2.%3.%4.',  'alignment' => 'left', 'tabPos' => 1440, 'left' => 1440, 'hanging' => 360],
                    ['format' => NumberFormat::UPPER_ROMAN,  'text' => '%1.%2.%3.%4.%5.','alignment' => 'left', 'tabPos' => 1800, 'left' => 1800, 'hanging' => 360],
                ],
            ]
        );
    }

    public function addTitleStyle(int $depth, ?array $style = [], ?array $pStyle = []): void
    {
        $this->phpWord->addTitleStyle($depth, array_merge(self::$MAP_STYLE_HEADING[$depth], $style), $pStyle);
    }

    public function readHTML(): self
    {
        foreach ($this->readDom->getNodes() as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }
            if ($node->parentNode instanceof DOMElement) {
                continue;
            }
            $this->renderBlock($node);
        }

        return $this;
    }

    /** Xử lý block tag (p, figure, table, ...) */
    protected function renderBlock(DOMElement $node, ?TextRun $textRun = null): void
    {
        /** @var TextRun $target */
        switch (strtolower($node->tagName)) {
            case 'p':
                $pStyle = self::buildParagraphStyleFromAttribute($node->getAttribute('style'));
                $this->createTextRun($pStyle);
                $this->renderInlineChildren($node, $this->defaultFontStyle);
                break;

            case 'figure':
            case 'div':
                foreach ($node->childNodes as $child) {
                    if ($child instanceof DOMElement) {
                        $this->renderBlock($child);
                    }
                }
                break;

            case 'ul':
            case 'ol':
                $this->renderNumbering($node);
                break;
            case 'table':
                $this->renderTable($node);
                break;
            case 'img':
                $this->renderImage($node, $textRun);
                break;
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
                $this->renderHeading($node);
                break;

            default:
                if (self::isInlineTag($node->tagName)) {
                    $this->renderInlineChildren($node, $this->defaultFontStyle);
                }
                break;
        }
    }

    protected function renderNumbering(DOMElement $node): void
    {
        $typeNumbering = self::$MAP_TAGNAME_NUMBERING[strtolower($node->tagName)];
        $listNumbering = self::buildDataNumbering($node);

        if($listNumbering === []){
            return;
        }

        foreach ($listNumbering as $value) {
            $listRun = $this->section->addListItemRun($value['level'], $typeNumbering);
            $this->renderListItemContent($value['value'], $listRun);
        }
    }

    /** Render nội dung inline trong <li>, bỏ qua ol/ul con (đã xử lý riêng ở buildDataNumbering). */
    protected function renderListItemContent(DOMElement $li, ListItemRun $listRun): void
    {
        foreach ($li->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text = $child->textContent;
                if ($text !== '') {
                    $listRun->addText($text);
                }
                continue;
            }

            if (!$child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);
            if (in_array($tag, ['ol', 'ul'], true)) {
                continue;
            }

            if ($tag === 'p') {
                $this->renderInlineChildren($child, [], $listRun);
                continue;
            }

            if (self::isInlineTag($child->tagName)) {
                $this->renderInlineChildren($child, [], $listRun);
            }
        }
    }

    protected function renderHeading(DOMElement $node): void
    {
        $depth = (int) substr($node->tagName, 1);
        $text = trim($node->textContent);
        $style = self::getStyleChildrenFromDOMElement($node);
        $pStyle = self::getStyleChildrenFromDOMElement($node, false);
        $this->addTitleStyle($depth, $style, $pStyle);
        if ($text !== '') {
            $this->createSection();
            $this->section->addTitle($text, $depth);
        }
        /** lý do tạo textRun mới nguyên nhân
         *  khi không tạo textRun mới thì sẽ là section->getElement = [ textRun(chính là $this->textRun), title ] và nếu dùng $this->textRun->addText("...") thì nó sẽ hiển thị ở bên trên title
         *  khi tạo textRun mới thì sẽ là section->getElement = [ textRun, title, textRun mới (chính là $this->textRun) ] và nếu dùng $this->textRun->addText("...") thì nó sẽ hiển thị ở bên dưới title
         */
        $this->createTextRun();
    }

    /**
     *
     * @param DOMElement $imageNode giá trị node img
     * @param ?TextRun $textRun giá trị textRun nếu không truyền thì sẽ sử dụng textRun mặc định
     * @return void
     * */
    protected function renderImage(DOMElement $imageNode, ?TextRun $textRun = null): void
    {
        $target = $textRun ?? $this->textRun;
        $style = $this->buildStyleImageFromDOMElement($imageNode);
        $target->addImage($this->getImageFilePath($imageNode->getAttribute('src')), $style);
    }

    /**
     * Build HTML table → PhpWord Table.
     *
     * Quy trình:
     * 1. Lấy danh sách <tr>
     * 2. Dựng ma trận ô (xử lý colspan / rowspan)
     * 3. Mỗi hàng ma trận → addRow() + addCell()
     * 4. Nội dung trong <td> render vào Cell
     */
    protected function renderTable(DOMElement $tableNode): void
    {
        $rows = $this->collectTableRows($tableNode);
        if ($rows === []) {
            return;
        }

        $matrix = $this->buildTableCellMatrix($rows);

        $tableStyle = array_merge([
            'borderSize' => 6,           // dày hơn, dễ thấy
            'borderColor' => '000000',
            'cellMargin' => 80,
            'unit' => TblWidth::PERCENT,
            'width' => 100 * 50,
        ], self::buildTableStyleFromAttribute($tableNode->getAttribute('style')));
        $table = $this->section->addTable($tableStyle);

        foreach ($matrix as $rowCells) {
            $rowStyle = [];
            $firstCell = $rowCells[0] ?? [];
            if (!empty($firstCell['isTheadRow'])) {
                $rowStyle['tblHeader'] = true;
            }
            $table->addRow(null, $rowStyle);

            foreach ($rowCells as $cellData) {

                $this->addTableCell($table, $cellData);
            }
        }

        // Tách paragraph sau bảng
        $this->createTextRun();
    }

    /**
     * Thêm một ô vào hàng PhpWord từ descriptor ma trận.
     *
     * @param array<string, mixed> $cellData
     */
    protected function addTableCell(Table $table, array $cellData): void
    {
        $colspan = (int) ($cellData['colspan'] ?? 1);
        $cellStyles = ['valign' => 'center'];

        if ($colspan > 1) {
            $cellStyles['gridSpan'] = $colspan;
        }

        if ($cellData['type'] === 'continue') {
            $cellStyles['vMerge'] = CellStyle::VMERGE_CONTINUE;
            $table->addCell(null, $cellStyles);
            return;
        }

        /** @var DOMElement $cellNode */
        $cellNode = $cellData['node'];
        $rowspan = (int) ($cellData['rowspan'] ?? 1);

        if ($rowspan > 1) {
            $cellStyles['vMerge'] = CellStyle::VMERGE_RESTART;
        }

        $cellStyles = array_merge(
            $cellStyles,
            self::buildCellStyleFromAttribute($cellNode->getAttribute('style'))
        );
        $cellTag = strtolower($cellData['cellTag'] ?? 'td');

        if ($cellTag === 'th') {
            $cellStyles['bgColor'] = $cellStyles['bgColor'] ?? 'F2F2F2';
        }
        $fontStyle = $this->resolveTableCellFontStyle($cellTag);
        // dump($cellStyles);
        $cell = $table->addCell(null, $cellStyles);
        $this->renderCellContent($cell, $cellNode, $fontStyle);
    }

    /**
     * Font style theo HTML thực tế:
     * - <th> trong <thead> (scope="col"): tiêu đề cột, in đậm
     * - <th scope="row"> trong <tbody>: tiêu đề dòng, in đậm
     * - <td>: dữ liệu, font mặc định (căn lề lấy từ <p style="text-align:...">)
     *
     * @return array<string, mixed>
     */
    protected function resolveTableCellFontStyle(string $cellTag): array
    {
        if ($cellTag !== 'th') {
            return $this->defaultFontStyle;
        }

        return self::mergeFontStyles($this->defaultFontStyle, ['bold' => true]);
    }

    /**
     * Render nội dung bên trong <td> / <th>.
     *
     * @param array<string, mixed> $fontStyle
     */
    protected function renderCellContent(Cell $cell, DOMElement $cellNode, array $fontStyle): void
    {
        $hasBlockChild = false;
        foreach ($cellNode->childNodes as $child) {
            if ($child instanceof DOMElement && strtolower($child->tagName) === 'p') {
                $hasBlockChild = true;
                break;
            }
        }

        if (!$hasBlockChild) {
            $textRun = $cell->addTextRun();
            $this->renderInlineChildren($cellNode, $fontStyle, $textRun);
            return;
        }

        foreach ($cellNode->childNodes as $child) {
            if (!$child instanceof DOMElement) {
                continue;
            }
            if (strtolower($child->tagName) === 'p') {
                $pStyle = self::buildParagraphStyleFromAttribute($child->getAttribute('style'));
                $textRun = $cell->addTextRun($pStyle);
                $this->renderInlineChildren($child, $fontStyle, $textRun);
            }
        }
    }

    /**
     * Duyệt con và addText với font style cộng dồn (i, strong, u, span, ...).
     *
     * @param array<string, mixed> $fontStyle
     */
    protected function renderInlineChildren(DOMElement $node, array $fontStyle, ?TextRun $textRun = null): void
    {
        $target = $textRun ?? $this->textRun;

        $mergedStyle = self::mergeFontStyles(
            $fontStyle,
            self::fontStyleFromTag($node->tagName),
            self::buildFontStyleFromAttribute($node->getAttribute('style'))
        );

        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text = $child->textContent;
                if ($text !== '') {
                    $target->addText($text, $mergedStyle);
                }
                continue;
            }

            if (!$child instanceof DOMElement) {
                continue;
            }


            if (strtolower($child->tagName) === 'br') {
                $target->addTextBreak();
                continue;
            }

            if (self::isInlineTag($child->tagName)) {
                $this->renderInlineChildren($child, $mergedStyle, $target);
            } elseif (self::isBlockTag($child->tagName)) {
                $this->renderBlock($child, $textRun);
            }
        }
    }

    public function export(string $filename = 'output.docx'): string
    {
        $dir = public_path('docx');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpWord, 'Word2007');
        $writer->save($fullPath);
        unset($writer);
        $this->readDom->cleanupTempImages();
        return $fullPath;
    }

    public function addFooter(): void
    {
        $footer = $this->section->addFooter();
        $table = $footer->addTable([
            'unit' => TblWidth::PERCENT,
            'width' => 100 * 50,
            'borderTopSize' => 6,
            'borderTopColor' => 'D9D9D9',
            'cellMarginTop' => 80,
        ]);
        $table->addRow();

        $left = $table->addCell(9000, ['valign' => 'center']);
        $left->addText(
            now()->format('d/m/Y | H:i:s'),
            ['name' => 'Times New Roman', 'size' => 9, 'color' => 'BFBFBF'],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START]
        );

        $right = $table->addCell(1000, ['valign' => 'center']);
        $right->addPreserveText(
            '{PAGE}',
            ['name' => 'Times New Roman', 'size' => 9, 'color' => 'BFBFBF'],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );
    }

    public function __destruct()
    {

    }
}
