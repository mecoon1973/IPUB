<?php

namespace ExportFile\phpExcel\Traits;

use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
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

        $sheet->setCellValue($coordinate, $value);

        return $sheet->getCell($coordinate);
    }

    /**
     * Tìm ô theo placeholder rồi gán giá trị mới.
     *
     * @return Cell Ô sau khi đã gán giá trị
     */
    public function replacePlaceholderValue(Worksheet $sheet, string $placeholder, mixed $value): Cell
    {
        $coordinate = $this->findPlaceholderCell($placeholder, $sheet);

        return $this->replaceCellValue($sheet, $coordinate, $value);
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

}
