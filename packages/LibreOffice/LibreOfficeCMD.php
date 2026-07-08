<?php

namespace LibreOffice;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Wrapper gọi LibreOffice CLI (soffice) để convert file office.
 *
 * LƯU Ý QUAN TRỌNG:
 * - LibreOffice Calc KHÔNG hỗ trợ convert trực tiếp xlsx → docx.
 *   Chuỗi đúng: xlsx → html (Calc) → docx (Writer).
 * - html → docx bắt buộc dùng filter "MS Word 2007 XML", không dùng "docx" đơn giản.
 * - html → rtf  bắt buộc dùng filter "Rich Text Format", không dùng "rtf" đơn giản.
 * - HTML xuất từ Calc thường tham chiếu ảnh PNG cùng thư mục; thiếu ảnh → logo không hiện trong docx.
 * - Layout bảng phức tạp (colspan/rowspan) có thể lệch; cân nhắc ExportWord (PhpWord) cho bước html → docx.
 *
 * Cấu hình .env bắt buộc:
 * - PATH_LIBRE_OFFICE=C:/Program Files/LibreOffice/program/soffice.exe
 * - OS_SYSTEM=Windows   (hoặc windows, không phân biệt hoa thường)
 * - LIBRE_OFFICE_TIMEOUT=120
 */
final class LibreOfficeCMD
{
    /** Định dạng đích: PDF */
    public const FORMAT_PDF = 'pdf';

    /** Định dạng đích: HTML */
    public const FORMAT_HTML = 'html';

    /** Định dạng đích: TXT (UTF-8) */
    public const FORMAT_TXT = 'txt';

    /** Định dạng đích: DOCX (Word) */
    public const FORMAT_DOCX = 'docx';

    /** Định dạng đích: RTF (Rich Text Format) */
    public const FORMAT_RTF = 'rtf';

    /**
     * Các extension file nguồn được phép.
     *
     * @var list<string>
     */
    private const SUPPORTED_INPUT_EXTENSIONS = ['xlsx', 'html'];

    /**
     * Filter LibreOffice tương ứng từng định dạng đích.
     *
     * Lưu ý: giá trị filter truyền nguyên văn cho --convert-to.
     * Không bọc dấu ngoặc kép bên trong (vd: docx:"MS Word 2007 XML" sẽ lỗi trên Windows).
     *
     * @var array<string, string>
     */
    private const OUTPUT_FILTERS = [
        self::FORMAT_PDF => 'pdf',
        self::FORMAT_HTML => 'html',
        self::FORMAT_TXT => 'txt:Text (encoded):UTF8',
        self::FORMAT_DOCX => 'docx:MS Word 2007 XML',
        self::FORMAT_RTF => 'rtf:Rich Text Format',
    ];

    /**
     * Ma trận convert hợp lệ: extension nguồn → danh sách định dạng đích.
     *
     * @var array<string, list<string>>
     */
    private const ALLOWED_CONVERSIONS = [
        'xlsx' => [self::FORMAT_PDF, self::FORMAT_HTML, self::FORMAT_TXT],
        'html' => [self::FORMAT_DOCX, self::FORMAT_RTF],
    ];

    /**
     * Convert file bằng LibreOffice headless CLI.
     *
     * Luồng hỗ trợ:
     * - xlsx → pdf | html | txt  (module Calc)
     * - html → docx | rtf           (module Writer)
     *
     * Ví dụ:
     *   LibreOfficeCMD::convert('E:\\file.xlsx', 'html');
     *   LibreOfficeCMD::convert('E:\\file.html', 'docx');
     *   LibreOfficeCMD::convert('E:\\file.html', 'rtf');
     *
     * @param string      $inputPath Đường dẫn tuyệt đối file nguồn (.xlsx hoặc .html)
     * @param string      $format    Định dạng đích (pdf, html, txt, docx, rtf)
     * @param string|null $outputDir Thư mục lưu file đích; null = cùng thư mục file nguồn
     *
     * @return string Đường dẫn tuyệt đối file sau khi convert
     *
     * @throws InvalidArgumentException File không tồn tại hoặc cặp input/output không hợp lệ
     * @throws RuntimeException         LibreOffice không tìm thấy, convert thất bại, hoặc không ra file
     */
    public static function convert(string $inputPath, string $format, ?string $outputDir = null): string
    {
        $inputPath = core_normalize_path($inputPath);
        $format = strtolower(trim($format));
        $inputExtension = strtolower(pathinfo($inputPath, PATHINFO_EXTENSION));

        if (!is_file($inputPath)) {
            throw new InvalidArgumentException("File nguồn không tồn tại: {$inputPath}");
        }

        if (!in_array($inputExtension, self::SUPPORTED_INPUT_EXTENSIONS, true)) {
            throw new InvalidArgumentException(
                'Định dạng nguồn không được hỗ trợ: ' . $inputExtension
                . '. Các định dạng hỗ trợ: ' . implode(', ', self::SUPPORTED_INPUT_EXTENSIONS)
            );
        }

        if (!isset(self::OUTPUT_FILTERS[$format])) {
            throw new InvalidArgumentException(
                'Định dạng đích không được hỗ trợ: ' . $format
                . '. Các định dạng hỗ trợ: ' . implode(', ', array_keys(self::OUTPUT_FILTERS))
            );
        }

        $allowedOutputs = self::ALLOWED_CONVERSIONS[$inputExtension] ?? [];
        if (!in_array($format, $allowedOutputs, true)) {
            throw new InvalidArgumentException(
                "Không thể convert {$inputExtension} sang {$format}. "
                . 'Các định dạng đích hợp lệ: ' . implode(', ', $allowedOutputs)
            );
        }

        $outputDir = $outputDir !== null ? core_normalize_path($outputDir) : dirname($inputPath);

        if (!is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
            throw new RuntimeException("Không thể tạo thư mục output: {$outputDir}");
        }

        $binary = self::resolveBinary();
        $convertTo = self::OUTPUT_FILTERS[$format];
        $expectedOutput = $outputDir . DIRECTORY_SEPARATOR . pathinfo($inputPath, PATHINFO_FILENAME) . '.' . $format;

        // UserInstallation: profile riêng tránh xung đột khi chạy headless song song hoặc trên Windows service.
        $arguments = [
            '-env:UserInstallation=' . self::buildUserInstallationUrl(),
            '--headless',
            '--nologo',
            '--nofirststartwizard',
            '--convert-to',
            $convertTo,
            '--outdir',
            $outputDir,
            $inputPath,
        ];

        $process = self::createProcess($binary, $arguments);
        $process->setTimeout((int) config('settings.libreoffice_timeout', 120));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                'LibreOffice convert thất bại (exit ' . $process->getExitCode() . '): '
                . trim($process->getErrorOutput() ?: $process->getOutput())
                . ' | command: ' . $process->getCommandLine()
            );
        }

        if (!is_file($expectedOutput)) {
            throw new RuntimeException("Convert xong nhưng không tìm thấy file: {$expectedOutput}");
        }

        return $expectedOutput;
    }

    /**
     * Lấy đường dẫn binary soffice từ config.
     *
     * Config: settings.path_libreoffice ← PATH_LIBRE_OFFICE trong .env
     *
     * Lưu ý:
     * - Windows: trỏ tới soffice.exe, vd: C:/Program Files/LibreOffice/program/soffice.exe
     * - Linux:   trỏ tới soffice hoặc libreoffice trong PATH
     * - Tự trim dấu ngoặc kép nếu .env bọc path trong quotes
     *
     * @throws RuntimeException Chưa cấu hình hoặc file binary không tồn tại
     */
    private static function resolveBinary(): string
    {
        $pathLibreoffice = trim((string) config('settings.path_libreoffice'), " \t\n\r\0\x0B\"'");

        if ($pathLibreoffice === '') {
            throw new RuntimeException(
                'Chưa set PATH_LIBRE_OFFICE trong .env, vui lòng set đường dẫn tới LibreOffice'
            );
        }

        if (!is_file($pathLibreoffice)) {
            throw new RuntimeException(
                'Không tìm thấy LibreOffice tại: ' . $pathLibreoffice
            );
        }

        return $pathLibreoffice;
    }

    /**
     * Kiểm tra môi trường đang chạy Windows.
     *
     * Dùng cho buildUserInstallationUrl (format file:/// khác nhau giữa Windows và Linux).
     * OS_SYSTEM trong .env chấp nhận cả "windows" lẫn "Windows".
     */
    private static function isWindows(): bool
    {
        $osSystem = strtolower((string) config('settings.os_system', PHP_OS_FAMILY));

        return $osSystem === 'windows' || PHP_OS_FAMILY === 'Windows';
    }

    /**
     * Tạo URL profile LibreOffice riêng cho mỗi lần chạy headless.
     *
     * Profile lưu tại: storage/app/libreoffice-profile
     *
     * Lưu ý:
     * - Tránh lock profile mặc định của user khi Apache/PHP-FPM gọi song song nhiều request.
     * - Windows cần prefix file:/// (3 dấu slash), Linux dùng file://
     *
     * @throws RuntimeException Không tạo được thư mục profile
     */
    private static function buildUserInstallationUrl(): string
    {
        $profileDir = storage_path('app/libreoffice-profile');

        if (!is_dir($profileDir) && !mkdir($profileDir, 0755, true) && !is_dir($profileDir)) {
            throw new RuntimeException("Không thể tạo thư mục LibreOffice profile: {$profileDir}");
        }

        $normalized = str_replace('\\', '/', $profileDir);

        if (self::isWindows()) {
            return 'file:///' . $normalized;
        }

        return 'file://' . $normalized;
    }

    /**
     * Khởi tạo Symfony Process với danh sách argument tách rời.
     *
     * Lưu ý:
     * - Dùng Process(array) thay vì fromShellCommandline để Symfony tự escape path có khoảng trắng
     *   (vd: C:\Program Files\LibreOffice\...).
     * - Không ghép command thành chuỗi shell — dễ lỗi quote trên Windows.
     *
     * @param string        $binary    Đường dẫn soffice.exe / soffice
     * @param list<string>  $arguments Các tham số CLI (--headless, --convert-to, ...)
     */
    private static function createProcess(string $binary, array $arguments): Process
    {
        return new Process(array_merge([$binary], $arguments));
    }
}
