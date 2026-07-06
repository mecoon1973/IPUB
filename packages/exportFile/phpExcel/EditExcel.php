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

    public function save(string $path): void
    {
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($path);
    }
}
