<?php

use ExportFile\ReadDOMDocument;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportExcel {

    /** Xuất file excel sử dụng PhpSpreadsheet */
    public Spreadsheet $phpExcel;

    /** Giá trị Sheet đang được chọn */
    protected Worksheet $sheet;

    /** Giá trị row đang được chọn */
    protected int $row;
    /** Giá trị column đang được chọn */
    protected string $column;

    /** ReadDOMDocument */
    protected ReadDOMDocument $readDom;

    public function __construct(string $html, string $sheetTitle = 'Sheet'){
        $this->phpExcel = new Spreadsheet();
        $this->sheet = $this->createSheet($sheetTitle);
        $this->row = 1;
        $this->column = 'A';
        $this->readDom = new ReadDOMDocument($html);
    }

    /** Tạo mới một sheet */
    public function createSheet(string $title, ?int $index = null): Worksheet
    {
        $sheet = new Worksheet($this->phpExcel, $title);
        if ($index === null) {
            $this->phpExcel->addSheet($sheet);
        } else {
            $this->phpExcel->addSheet($sheet, $index);
        }
        return $sheet;
    }

    /** Thay đổi sheet đang được chọn */
    public function changeSheet(int $index): void
    {
        $this->sheet = $this->phpExcel->getSheet($index);
    }

    /** Lấy sheet đang được chọn */
    public function getActiveSheet(): Worksheet
    {
        return $this->sheet;
    }

    /** Thay đổi row đang được chọn */
    public function changeRow(int $row): void
    {
        $this->row = $row;
    }

    /** Thay đổi column đang được chọn */
    public function changeColumn(string $column): void
    {
        $this->column = $column;
    }

    /** Lấy row đang được chọn */
    public function getRow(): int
    {
        return $this->row;
    }

    /** Lấy column đang được chọn */
    public function getColumn(): string
    {
        return $this->column;
    }


    
    public function export(string $filename = 'output.xlsx'): string{
        $dir = public_path('xlsx');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->phpExcel, 'Xlsx');
        // $writer->save($fullPath);
        // unset($writer);
        return "";
    }
}
