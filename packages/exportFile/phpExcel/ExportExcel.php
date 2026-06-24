<?php

use ExportFile\ReadDOMDocument;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class ExportExcel {

    /** Xuất file excel sử dụng PhpSpreadsheet */
    protected Spreadsheet $phpExcel;

    /** ReadDOMDocument */
    protected ReadDOMDocument $readDom;

    public function __construct(string $html){
        $this->phpExcel = new Spreadsheet();
        $this->readDom = new ReadDOMDocument($html);
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
