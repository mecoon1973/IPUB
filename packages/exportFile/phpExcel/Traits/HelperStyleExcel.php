<?php

namespace ExportFile\phpExcel\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DOMElement;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait HelperStyleExcel {

    /** xóa tất cả các border trong sheet */
    public static function removeAllBorders(Worksheet &$sheet): void
    {
        $range = 'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow();
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_NONE,
                ],
            ],
        ]);

    }

    /** Các thuộc tính trong cell. (chữ: font, size, đậm, nghiêng, gạch chân, màu, ...) */
    public static function setFont(string $key, string|int|bool $value): array {
        switch ($key) {
            case 'name': // string: Arial, Times New Roman, ...
                return  ['name' => $value];
            case 'size': // int/float
                return  ['size' => $value];
            case 'italic': // bool
                return  ['italic' => $value];
            case 'bold': // bool
                return  ['bold' => $value];
            case 'underline': // constant
                return  ['underline' => Font::UNDERLINE_SINGLE];
            case 'strikethrough': // bool
                return  ['strikethrough' => $value];
            case 'superscript': // bool
                return  ['superscript' => $value];
            case 'subscript': // bool
                return  ['subscript' => $value];
            case 'color': // ['argb' => '...']
                return  [ 'color' => ['argb' => $value]]; // FFFF0000 => red
            default:
                return [];
        }
    }

    /** Các thuộc tính. Nền ô: màu, pattern, gradient */
    public static function setFill(string $key, string|int|bool $value): array {
        switch ($key) {
            case 'fillType': // constant
                return  [ 'fillType' =>  Fill::FILL_SOLID];
            case 'startColor': // ['argb' => '...']
                return  ['startColor' => ['argb' => $value]]; // FFFF0000 => red
            case 'endColor': // ['argb' => '...']
                return  ['endColor' => ['argb' => $value]]; // FFFF0000 => red
            default:
                return [];
        }
    }

    /** Các thuộc tính. Căn lề: horizontal, vertical, textRotation, wrapText, shrinkToFit, indent */
    public static function setAlignment(string $key, string|int|bool $value): array {
        switch ($key) {
            /** căn ngang */
            case 'horizontal': // constant
                return [ 'horizontal' =>  Alignment::HORIZONTAL_CENTER];
            /** căn dọc */
            case 'vertical': // constant
                return [ 'vertical' => Alignment::VERTICAL_CENTER];
            /** xoay chữ */
            case 'textRotation': // int
                return [ 'textRotation' => $value];
            /** Tự xuống dòng */
            case 'wrapText': // bool
                return [ 'wrapText' => $value];
            /** thu gọn chữ vừa ô */
            case 'shrinkToFit': // bool
                return [ 'shrinkToFit' => $value];
            /** thụt lề */
            case 'indent': // int
                return [ 'indent' => $value];
            default:
                return [];
        }
    }

    /** Các thuộc tính. định dạng số
     * example:
     *     0: số nguyên
     *     0.00: số thập phân 2 chữ số
     *     #,##0: có dấu phẩy ngăn nghìn
     *     #,##0.00: Tiền / số thập phân
     *     0%: phần trăm
     *     dd/mm/yyyy: ngày
     *     hh:mm:ss: giờ
     *     @: text
     *     #,##0 "đ": Số + đơn vị
     *
    */
    public static function setNumberFormat(string $key, string|int|bool $value): array {
        switch ($key) {
            case 'formatCode': // string
                return  [ 'formatCode' => $value];
            default:
                return [];
        }
    }

    /** Các thuộc tính. Căn lề: horizontal, vertical, textRotation, wrapText, shrinkToFit, indent */
    public static function setBorder(string $key, string|int|bool $value): array {
        switch ($key) {
            case 'allBorders': // string
                return  [ ];
            case 'top': // string
                return  [ ];
            case 'bottom': // string
                return  [ ];
            case 'right': // string
                return  [ ];
            case 'left': // string
                return  [ ];
            default:
                return [];
        }
    }

}
