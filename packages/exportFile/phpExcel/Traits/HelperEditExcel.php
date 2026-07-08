<?php

namespace ExportFile\phpExcel\Traits;

use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;

trait HelperEditExcel {

    /**
     * Gán giá trị mới cho ô trên sheet (giữ nguyên style hiện có của ô).
     *
     * @param Worksheet $sheet      Sheet cần sửa
     * @param string    $coordinate Vị trí ô, vd: "C5"
     * @param mixed     $value      Giá trị thay thế (string, int, float, bool, null)
     *
     * @return Cell Ô sau khi đã gán giá trị
     */
    public function replaceCellValue(Worksheet $sheet, string $coordinate, mixed $value): Cell
    {
        $coordinate = strtoupper(trim($coordinate));

        if (!preg_match('/^[A-Z]{1,3}[1-9]\d*$/', $coordinate)) {
            throw new InvalidArgumentException('Địa chỉ cell không hợp lệ: ' . $coordinate);
        }

        $displayValue = $this->normalizeReplacementValue($value);
        $sheet->setCellValue($coordinate, $displayValue);
        $this->applyCellFullContentDisplay($sheet, $coordinate, $displayValue);

        return $sheet->getCell($coordinate);
    }

    /**
     * Tìm ô theo placeholder rồi thay placeholder bằng $value, giữ nguyên phần text còn lại trong ô.
     *
     * Ví dụ: ô "Sách: [!TenSach!] - CXB" + placeholder "[!TenSach!]" + value "Toán 1"
     *      → "Sách: Toán 1 - CXB"
     *
     * @return Cell Ô sau khi đã thay thế
     */
    public function replacePlaceholderValue(Worksheet $sheet, string $placeholder, mixed $value): Cell
    {
        $coordinate = $this->findPlaceholderCell($placeholder, $sheet);
        $cell = $sheet->getCell($coordinate);
        $currentValue = $this->cellValueToString($cell->getValue());

        if (!str_contains($currentValue, $placeholder)) {
            throw new RuntimeException(
                'Ô "' . $coordinate . '" không chứa placeholder "' . $placeholder . '".'
            );
        }

        $replacement = $this->normalizeReplacementValue($value);
        $newValue = str_replace($placeholder, $replacement, $currentValue);

        return $this->replaceCellValue($sheet, $coordinate, $newValue);
    }

    /**
     * Lấy object Cell chứa placeholder.
     *
     * @throws RuntimeException Không tìm thấy ô khớp placeholder
     */
    public function findPlaceholderCellObject(string $placeholder, Worksheet $sheet): Cell
    {
        return $sheet->getCell($this->findPlaceholderCell($placeholder, $sheet));
    }

    /**
     * Tìm ô đầu tiên trong sheet chứa placeholder (mặc định: $title$).
     *
     * @param string         $placeholder Giá trị cần tìm, vd: $title$
     * @param Worksheet $sheet       Sheet cần quét;
     *
     * @return string Địa chỉ cell, vd: "C5"
     *
     * @throws RuntimeException Không tìm thấy ô khớp placeholder
     */
    public function findPlaceholderCell(string $placeholder, Worksheet $sheet): string
    {
        if ($placeholder === '') {
            throw new InvalidArgumentException('Placeholder không được rỗng.');
        }


        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                if (!$cell instanceof Cell) {
                    continue;
                }

                if ($this->cellContainsPlaceholder($cell, $placeholder)) {
                    return $cell->getCoordinate();
                }
            }
        }

        throw new RuntimeException(
            'Không tìm thấy ô chứa placeholder "' . $placeholder . '" trong sheet "' . $sheet->getTitle() . '".'
        );
    }

    /**
     * Kiểm tra xem ô có chứa placeholder không
     * @param Cell $cell ô cần kiểm tra
     * @param string $placeholder placeholder cần tìm
     * @return bool true nếu ô có chứa placeholder, false nếu không
     */
    protected function cellContainsPlaceholder(Cell $cell, string $placeholder): bool
    {
        $value = $this->cellValueToString($cell->getValue());

        return $value === $placeholder || str_contains($value, $placeholder);
    }

    /**
     * Chuyển giá trị của ô thành string
     * @param mixed $value giá trị của ô
     * @return string giá trị của ô thành string
     */
    protected function cellValueToString(mixed $value): string
    {
        if ($value instanceof RichText) {
            return $value->getPlainText();
        }

        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }

    /**
     * Chuẩn hóa giá trị chèn vào Excel: cast string + decode HTML entities từ DB.
     *
     * Ví dụ: "L&#234; Hồng Mai" → "Lê Hồng Mai"
     */
    protected function normalizeReplacementValue(mixed $value): string
    {
        return $this->decodeHtmlEntities($this->cellValueToString($value));
    }

    /**
     * Giải mã HTML entities (&#234;, &amp;, &quot;...) sang ký tự UTF-8 thường.
     */
    protected function decodeHtmlEntities(string $text): string
    {
        if ($text === '') {
            return '';
        }

        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Chỉ chỉnh style/chiều cao khi nội dung dự kiến không vừa hàng hiện tại.
     * Nếu đủ chỗ → giữ nguyên thuộc tính ô từ template.
     */
    protected function applyCellFullContentDisplay(Worksheet $sheet, string $coordinate, string $text): void
    {
        if ($text === '') {
            return;
        }

        $styleRange = $this->resolveCellStyleRange($sheet, $coordinate);
        $estimatedHeight = $this->estimateWrappedContentHeight($sheet, $styleRange, $text);
        $currentHeight = $this->getStyleRangeRowHeight($sheet, $styleRange);

        if ($estimatedHeight <= $currentHeight) {
            return;
        }

        $alignment = $sheet->getStyle($styleRange)->getAlignment();
        $alignment->setWrapText(true);
        $alignment->setVertical(Alignment::VERTICAL_CENTER);
        $alignment->setShrinkToFit(false);

        $this->setStyleRangeRowHeight($sheet, $styleRange, $estimatedHeight);
    }

    /**
     * Trả về range merge chứa ô (vd: J12:T12), hoặc chính ô nếu không merge.
     */
    protected function resolveCellStyleRange(Worksheet $sheet, string $coordinate): string
    {
        [$column, $row] = Coordinate::coordinateFromString($coordinate);
        $columnIndex = Coordinate::columnIndexFromString($column);

        foreach ($sheet->getMergeCells() as $mergeRange) {
            [$start, $end] = Coordinate::rangeBoundaries($mergeRange);
            if (
                $columnIndex >= $start[0] && $columnIndex <= $end[0]
                && $row >= $start[1] && $row <= $end[1]
            ) {
                return $mergeRange;
            }
        }

        return $coordinate;
    }

    /**
     * Tổng độ rộng cột của range (cộng dồn nếu là ô merge ngang).
     */
    protected function getEffectiveColumnWidthForStyleRange(Worksheet $sheet, string $styleRange): float
    {
        if (!str_contains($styleRange, ':')) {
            [$column] = Coordinate::coordinateFromString($styleRange);

            return $this->getColumnWidthOrDefault($sheet, $column);
        }

        [$start, $end] = Coordinate::rangeBoundaries($styleRange);
        $totalWidth = 0.0;
        for ($columnIndex = $start[0]; $columnIndex <= $end[0]; $columnIndex++) {
            $column = Coordinate::stringFromColumnIndex($columnIndex);
            $totalWidth += $this->getColumnWidthOrDefault($sheet, $column);
        }

        return $totalWidth;
    }

    protected function getColumnWidthOrDefault(Worksheet $sheet, string $column): float
    {
        $width = $sheet->getColumnDimension($column)->getWidth();

        return $width > 0 ? $width : 8.43;
    }

    /**
     * Ước lượng chiều cao cần thiết khi bật wrap text.
     */
    protected function estimateWrappedContentHeight(Worksheet $sheet, string $styleRange, string $text): float
    {
        $totalWidth = $this->getEffectiveColumnWidthForStyleRange($sheet, $styleRange);
        $fontSize = $sheet->getStyle($styleRange)->getFont()->getSize();
        if ($fontSize <= 0) {
            $fontSize = 11;
        }

        $charsPerLine = max(1, (int) floor($totalWidth * 0.9));
        $lineCount = 0;
        foreach (preg_split("/\r\n|\n|\r/", $text) as $line) {
            $lineCount += max(1, (int) ceil(mb_strlen($line) / $charsPerLine));
        }

        return ($lineCount * $fontSize * 1.5) + 6;
    }

    /**
     * Tổng chiều cao các hàng trong range (ô merge dọc cộng dồn).
     */
    protected function getStyleRangeRowHeight(Worksheet $sheet, string $styleRange): float
    {
        [$startRow, $endRow] = $this->getStyleRangeRowBounds($styleRange);
        $totalHeight = 0.0;

        for ($row = $startRow; $row <= $endRow; $row++) {
            $rowHeight = $sheet->getRowDimension($row)->getRowHeight();
            $totalHeight += $rowHeight > 0 ? $rowHeight : 15;
        }

        return $totalHeight;
    }

    /**
     * @return array{0: int, 1: int} [startRow, endRow]
     */
    protected function getStyleRangeRowBounds(string $styleRange): array
    {
        if (str_contains($styleRange, ':')) {
            [, $startRow] = Coordinate::coordinateFromString(explode(':', $styleRange)[0]);
            [, $endRow] = Coordinate::coordinateFromString(explode(':', $styleRange)[1]);

            return [$startRow, $endRow];
        }

        [, $row] = Coordinate::coordinateFromString($styleRange);

        return [$row, $row];
    }

    /**
     * Tăng chiều cao hàng khi nội dung vượt quá kích thước hiện tại.
     */
    protected function setStyleRangeRowHeight(Worksheet $sheet, string $styleRange, float $estimatedHeight): void
    {
        [$startRow, $endRow] = $this->getStyleRangeRowBounds($styleRange);

        for ($row = $startRow; $row <= $endRow; $row++) {
            $rowDimension = $sheet->getRowDimension($row);
            $currentHeight = $rowDimension->getRowHeight();
            if ($currentHeight <= 0) {
                $currentHeight = 15;
            }

            if ($estimatedHeight > $currentHeight) {
                $rowDimension->setRowHeight($estimatedHeight);
            }
        }
    }

    /**
     * Nhân bản các ô trên một hàng template và chèn bản sao xuống các hàng bên dưới.
     *
     * @param array $templateCellCoordinates Danh sách ô trên cùng một hàng, vd: ["A9", "B9", "C9"]
     * @param int   $duplicateCount          Số hàng bản sao chèn thêm (không tính hàng template gốc)
     */
    public function performDuplicateRowCellsBelow(
        Worksheet $sheet,
        array $templateCellCoordinates,
        int $duplicateCount
    ): void {
        if ($duplicateCount <= 0) {
            return;
        }

        if ($templateCellCoordinates === []) {
            throw new InvalidArgumentException('Danh sách ô template không được rỗng.');
        }

        $templateRow = null;
        $columns = [];

        foreach ($templateCellCoordinates as $coordinate) {
            $coordinate = strtoupper(trim((string) $coordinate));

            if (!preg_match('/^[A-Z]{1,3}[1-9]\d*$/', $coordinate)) {
                throw new InvalidArgumentException('Địa chỉ cell không hợp lệ: ' . $coordinate);
            }

            [$column, $row] = Coordinate::coordinateFromString($coordinate);

            if ($templateRow === null) {
                $templateRow = $row;
            } elseif ($templateRow !== $row) {
                throw new InvalidArgumentException('Tất cả ô template phải nằm trên cùng một hàng.');
            }

            $columns[] = $column;
        }

        $columns = array_values(array_unique($columns));
        $templateRowHeight = $sheet->getRowDimension($templateRow)->getRowHeight();

        $sheet->insertNewRowBefore($templateRow + 1, $duplicateCount);

        for ($offset = 1; $offset <= $duplicateCount; $offset++) {
            $targetRow = $templateRow + $offset;

            if ($templateRowHeight > 0) {
                $sheet->getRowDimension($targetRow)->setRowHeight($templateRowHeight);
            }

            foreach ($columns as $column) {
                $this->copyCellToRow($sheet, $column . $templateRow, $column . $targetRow);
            }

            $this->duplicateHorizontalMergesOnRow($sheet, $templateRow, $targetRow);
        }
    }

    /**
     * Sao chép giá trị và style từ ô nguồn sang ô đích.
     */
    protected function copyCellToRow(Worksheet $sheet, string $sourceCoordinate, string $targetCoordinate): void
    {
        $sourceCell = $sheet->getCell($sourceCoordinate);
        $targetCell = $sheet->getCell($targetCoordinate);

        $sheet->duplicateStyle($sheet->getStyle($sourceCoordinate), $targetCoordinate);
        $targetCell->setValue($sourceCell->getValue());
        $targetCell->setDataType($sourceCell->getDataType());
    }

    /**
     * Nhân bản các vùng merge ngang trên hàng template sang hàng đích.
     */
    protected function duplicateHorizontalMergesOnRow(Worksheet $sheet, int $templateRow, int $targetRow): void
    {
        foreach ($sheet->getMergeCells() as $mergeRange) {
            [$start, $end] = Coordinate::rangeBoundaries($mergeRange);

            if ($start[1] !== $templateRow || $end[1] !== $templateRow) {
                continue;
            }

            $startColumn = Coordinate::stringFromColumnIndex($start[0]);
            $endColumn = Coordinate::stringFromColumnIndex($end[0]);
            $targetRange = $startColumn . $targetRow . ':' . $endColumn . $targetRow;

            if (!isset($sheet->getMergeCells()[$targetRange])) {
                $sheet->mergeCells($targetRange);
            }
        }
    }

    /**
     * Ghi matrix dữ liệu vào các hàng bắt đầu từ hàng template.
     *
     * @param array $columnKeys      Key từ map_foreach (ô hoặc placeholder)
     * @param array $rowValuesMatrix [rowIndex][colIndex] = value
     */
    protected function performFillDuplicatedRowValues(
        Worksheet $sheet,
        array $columnKeys,
        array $rowValuesMatrix
    ): void {
        if ($columnKeys === [] || $rowValuesMatrix === []) {
            return;
        }

        $templateRow = null;
        $columnMeta = [];

        foreach ($columnKeys as $columnKey) {
            $coordinate = $this->resolveColumnKeyToCoordinate($sheet, (string) $columnKey);
            [$column, $row] = Coordinate::coordinateFromString($coordinate);

            if ($templateRow === null) {
                $templateRow = $row;
            } elseif ($templateRow !== $row) {
                throw new InvalidArgumentException('Tất cả cột template phải nằm trên cùng một hàng.');
            }

            $columnMeta[] = [
                'key' => (string) $columnKey,
                'column' => $column,
            ];
        }

        foreach ($rowValuesMatrix as $rowIndex => $rowValues) {
            if (!is_array($rowValues)) {
                continue;
            }

            $targetRow = $templateRow + $rowIndex;

            foreach ($columnMeta as $colIndex => $meta) {
                $coordinate = $meta['column'] . $targetRow;
                $value = $rowValues[$colIndex] ?? '';

                if ($this->isCellCoordinate($meta['key'])) {
                    $this->replaceCellValue($sheet, $coordinate, $value);
                } else {
                    $this->replacePlaceholderValueAtCoordinate($sheet, $coordinate, $meta['key'], $value);
                }
            }
        }
    }

    /**
     * @param array<int, string> $columnKeys
     *
     * @return array<int, string>
     */
    protected function resolveColumnKeysToCoordinates(Worksheet $sheet, array $columnKeys): array
    {
        $coordinates = [];

        foreach ($columnKeys as $columnKey) {
            $coordinates[] = $this->resolveColumnKeyToCoordinate($sheet, (string) $columnKey);
        }

        return $coordinates;
    }

    protected function resolveColumnKeyToCoordinate(Worksheet $sheet, string $columnKey): string
    {
        $columnKey = trim($columnKey);

        if ($this->isCellCoordinate($columnKey)) {
            return strtoupper($columnKey);
        }

        return $this->findPlaceholderCell($columnKey, $sheet);
    }

    protected function isCellCoordinate(string $key): bool
    {
        return (bool) preg_match('/^[A-Z]{1,3}[1-9]\d*$/i', trim($key));
    }

    /**
     * Thay placeholder tại một ô cụ thể (không quét toàn sheet).
     */
    protected function replacePlaceholderValueAtCoordinate(
        Worksheet $sheet,
        string $coordinate,
        string $placeholder,
        mixed $value
    ): Cell {
        $cell = $sheet->getCell($coordinate);
        $currentValue = $this->cellValueToString($cell->getValue());

        if (str_contains($currentValue, $placeholder)) {
            $replacement = $this->normalizeReplacementValue($value);
            $newValue = str_replace($placeholder, $replacement, $currentValue);

            return $this->replaceCellValue($sheet, $coordinate, $newValue);
        }

        return $this->replaceCellValue($sheet, $coordinate, $value);
    }

}
