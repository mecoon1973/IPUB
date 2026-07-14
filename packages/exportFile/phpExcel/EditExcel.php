<?php

namespace ExportFile\phpExcel;

use ExportFile\phpExcel\Traits\HelperEditExcel;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;

/**
 * Chỉnh sửa file Excel có sẵn (.xlsx) bằng PhpSpreadsheet.
 */
class EditExcel {
    use HelperEditExcel;

    /** Đường dẫn tuyệt đối file excel gốc */
    protected string $originalPath;

    /** Spreadsheet đọc từ file gốc */
    protected Spreadsheet $spreadsheet;

    /** Sheet đang hoạt động */
    protected Worksheet $sheet;


    /**
     * @param string $originalPath Đường dẫn tuyệt đối file .xlsx
     */
    public function __construct(string $originalPath)
    {
        $this->originalPath = core_normalize_path($originalPath);
        assert_file_exists($this->originalPath, 'xlsx');
        $this->spreadsheet = $this->loadSpreadsheet($this->originalPath);
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function getOriginalPath(): string
    {
        return $this->originalPath;
    }

    public function getSpreadsheet(): Spreadsheet
    {
        return $this->spreadsheet;
    }

    public function getSheet(): Worksheet
    {
        return $this->sheet;
    }

    /**
     * Đọc file .xlsx vào Spreadsheet.
     */
    protected function loadSpreadsheet(string $path): Spreadsheet
    {
        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Throwable $exception) {
            throw new RuntimeException(
                'Không thể đọc file Excel: ' . $path . ' — ' . $exception->getMessage(),
                0,
                $exception
            );
        }

        if (!$spreadsheet instanceof Spreadsheet) {
            throw new RuntimeException('File Excel không hợp lệ: ' . $path);
        }

        return $spreadsheet;
    }

    /**
     * Thêm nội dung vào sheet
     * @param array $mapPlaceholderValue
     */
    public function appendContentToSheet(array $mapPlaceholderValue): void
    {
        foreach($mapPlaceholderValue as $placeholder => $value){
            $this->replacePlaceholderValue($this->sheet, $placeholder, $value);
        }
    }

    /**
     * Nhân bản hàng template (theo danh sách ô) và chèn các bản sao xuống phía dưới.
     *
     * Dùng cho map_foreach: hàng gốc giữ placeholder, các hàng bên dưới là bản sao
     * để điền dữ liệu từng phần tử trong mảng.
     *
     * @param array $templateCellCoordinates Các ô trên cùng một hàng template, vd: ["A9", "B9", "C9"]
     * @param int   $duplicateCount          Số hàng bản sao chèn thêm bên dưới (không tính hàng gốc).
     *                                       Ví dụ mảng 10 phần tử → $duplicateCount = 9.
     */
    public function duplicateRowCellsBelow(array $templateCellCoordinates, int $duplicateCount): void
    {
        $resolvedCoordinates = $this->resolveColumnKeysToCoordinates($this->sheet, $templateCellCoordinates);
        if ($resolvedCoordinates === []) {
            return;
        }
        $this->performDuplicateRowCellsBelow($this->sheet, $resolvedCoordinates, $duplicateCount);
    }

    /**
     * Ghi dữ liệu vào các hàng đã nhân bản từ template (map_foreach).
     *
     * @param array $columnKeys       Key từ map_foreach: ô "A9" hoặc placeholder "[!a!]"
     * @param array $rowValuesMatrix  Mỗi phần tử là 1 hàng, thứ tự cột khớp $columnKeys
     */
    public function fillDuplicatedRowValues(array $columnKeys, array $rowValuesMatrix): void
    {
        $this->performFillDuplicatedRowValues($this->sheet, $columnKeys, $rowValuesMatrix);
    }

    public function save(string $path): void
    {
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($path);
    }
}
