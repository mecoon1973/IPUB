<?php

namespace ExportFile\html;

use LibreOffice\LibreOfficeCMD;
use RuntimeException;

/**
 * Pipeline HTML → DOCX: chuẩn hóa HTML, convert LibreOffice.
 */
final class HtmlToDocxExporter
{
    /**
     * @param string      $htmlPath  File HTML nguồn (tuyệt đối)
     * @param string|null $outputDir Thư mục lưu DOCX; null = cùng thư mục file HTML
     */
    public static function convert(
        string $htmlPath,
        ?string $outputDir = null,
        string $pageMargin = '0.5cm'
    ): string {
        $htmlPath = core_normalize_path($htmlPath);
        if (!is_file($htmlPath)) {
            throw new RuntimeException('File HTML không tồn tại: ' . $htmlPath);
        }

        $outputDir = $outputDir !== null ? core_normalize_path($outputDir) : dirname($htmlPath);
        if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
            throw new RuntimeException('Không thể tạo thư mục output: ' . $outputDir);
        }

        $editHtml = new EditHtml($htmlPath);
        $editHtml->prepareForLibreOfficeDocxExport($pageMargin);

        $preparedPath = $outputDir . DIRECTORY_SEPARATOR
            . pathinfo($htmlPath, PATHINFO_FILENAME) . '_prepared.html';
        $editHtml->save($preparedPath);

        return LibreOfficeCMD::convert($preparedPath, LibreOfficeCMD::FORMAT_DOCX, $outputDir);
    }
}
