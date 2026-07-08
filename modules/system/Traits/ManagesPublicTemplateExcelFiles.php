<?php

namespace Modules\System\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Modules\System\Model\DM_TEMPLATE_EXCEL;

/**
 * Upload, lưu trữ và dọn dẹp file template Excel trong public/template/excel.
 *
 * - Đặt tên file theo key template (đã sanitize)
 * - Ghi đè file cũ cùng key khi upload lại
 * - Trả URL public có domain (APP_URL)
 * - Dọn file vật lý khi cập nhật key hoặc path_file_template
 */
trait ManagesPublicTemplateExcelFiles
{
    /**
     * Upload file template Excel và lưu vào public/template/excel.
     *
     * Luồng xử lý:
     * 1. Validate key và định dạng file (.xlsx, .xls)
     * 2. Chuẩn hóa key thành tên file an toàn (sanitizeTemplateKey)
     * 3. Lấy/tao thư mục lưu trữ (getTemplateExcelDirectory)
     * 4. Xóa file cũ cùng key nếu đã tồn tại (removeExistingTemplateFiles)
     * 5. Di chuyển file upload vào thư mục đích
     * 6. Trả về URL đầy đủ (có domain) để frontend tải/xem file
     *
     * @param UploadedFile $file File Excel người dùng upload từ form
     * @param string $key Mã template — dùng làm tên file (vd: "import-sach" → import-sach.xlsx)
     * @return string URL public, vd: http://localhost/template/excel/import-sach.xlsx
     * @throws Exception Khi key rỗng, định dạng file không hợp lệ, hoặc key sau sanitize không còn ký tự hợp lệ
     */
    public function uploadTemplate(UploadedFile $file, string $key): string {
        $normalizedKey = trim($key);
        if ($normalizedKey === "") {
            throw new Exception("Key template không được để trống");
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ["xlsx", "xls"], true)) {
            throw new Exception("Chỉ chấp nhận file Excel (.xlsx, .xls)");
        }

        $safeKey = $this->sanitizeTemplateKey($normalizedKey);
        $fileName = $safeKey . "." . $extension;
        $directory = $this->getTemplateExcelDirectory();

        $this->removeExistingTemplateFiles($directory, $safeKey);

        $file->move($directory, $fileName);

        return url("template/excel/" . $fileName);
    }

    /**
     * Dọn file template vật lý không còn dùng khi cập nhật bản ghi.
     *
     * - Đổi key: xóa file .xlsx/.xls của key cũ trong public/template/excel
     * - Đổi path_file_template: xóa file cũ nếu khác file mới (tránh sót file khi đổi URL/đuôi)
     */
    protected function cleanupObsoleteTemplateFilesOnUpdate(DM_TEMPLATE_EXCEL $existing, array $data): void {
        $directory = $this->getTemplateExcelDirectory();
        $oldKey = trim((string) $existing->key);
        $newKey = trim((string) data_get($data, "key", ""));
        $oldPath = trim((string) $existing->path_file_template);
        $newPath = trim((string) data_get($data, "path_file_template", ""));

        if ($oldKey !== "" && $newKey !== "" && $oldKey !== $newKey) {
            $oldSafeKey = $this->sanitizeTemplateKey($oldKey);
            $newSafeKey = $this->sanitizeTemplateKey($newKey);
            if ($oldSafeKey !== $newSafeKey) {
                $this->removeExistingTemplateFiles($directory, $oldSafeKey);
            }
        }

        if ($oldPath !== "" && $newPath !== "" && $oldPath !== $newPath) {
            $this->deleteTemplateFileByPath($oldPath, $newPath);
        }
    }

    /**
     * Lấy đường dẫn tuyệt đối thư mục lưu template Excel trong public.
     *
     * Thư mục: {project}/public/template/excel
     * Tự tạo thư mục (mkdir recursive) nếu chưa tồn tại.
     *
     * @return string Đường dẫn tuyệt đối, vd: C:\xampp7.4\htdocs\laravel-12\public\template\excel
     */
    private function getTemplateExcelDirectory(): string {
        $directory = public_path("template/excel");
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        return $directory;
    }

    /**
     * Chuẩn hóa key template thành tên file an toàn trên hệ điều hành.
     *
     * Quy tắc:
     * - Chỉ giữ chữ cái, số, gạch dưới (_) và gạch ngang (-)
     * - Ký tự khác (khoảng trắng, dấu tiếng Việt, ký tự đặc biệt) → thay bằng _
     * - Bỏ _ thừa ở đầu/cuối
     *
     * @throws Exception Khi sau sanitize không còn ký tự hợp lệ
     */
    private function sanitizeTemplateKey(string $key): string {
        $safeKey = preg_replace('/[^a-zA-Z0-9_-]+/', "_", $key);
        $safeKey = trim((string) $safeKey, "_");

        if ($safeKey === "") {
            throw new Exception("Key template không hợp lệ");
        }

        return $safeKey;
    }

    /**
     * Xóa file template cũ cùng key trước khi upload file mới (ghi đè theo key).
     */
    private function removeExistingTemplateFiles(string $directory, string $safeKey): void {
        foreach (["xlsx", "xls"] as $extension) {
            $existingPath = $directory . DIRECTORY_SEPARATOR . $safeKey . "." . $extension;
            if (file_exists($existingPath)) {
                unlink($existingPath);
            }
        }
    }

    /**
     * Xóa file template theo path cũ (URL hoặc đường dẫn tuyệt đối).
     * Bỏ qua nếu path cũ và path mới trỏ cùng một file trên disk.
     */
    private function deleteTemplateFileByPath(string $oldPath, string $newPath = ""): void {
        $oldAbsolutePath = $this->resolveTemplateFileAbsolutePath($oldPath);
        if ($oldAbsolutePath === null || !file_exists($oldAbsolutePath)) {
            return;
        }

        if ($newPath !== "") {
            $newAbsolutePath = $this->resolveTemplateFileAbsolutePath($newPath);
            if ($newAbsolutePath !== null && $oldAbsolutePath === $newAbsolutePath) {
                return;
            }
        }

        unlink($oldAbsolutePath);
    }

    /**
     * Chuyển path_file_template (URL / path tuyệt đối / path public) sang đường dẫn file trên disk.
     */
    public function resolveTemplateFileAbsolutePath(string $path): ?string {
        $path = trim($path);
        if ($path === "") {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            $parsedPath = parse_url($path, PHP_URL_PATH);
            if (!is_string($parsedPath) || $parsedPath === "") {
                return null;
            }
            $path = $parsedPath;
        }

        if (file_exists($path)) {
            return $path;
        }

        $normalized = ltrim(str_replace("\\", "/", $path), "/");
        $publicPath = public_path(str_replace("/", DIRECTORY_SEPARATOR, $normalized));
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        return null;
    }

    /**
     * Chuyển URL/path template Excel sang đường dẫn tuyệt đối trên máy chủ.
     *
     * Chỉ lấy phần path từ URL (bỏ domain), rồi map vào thư mục public của project.
     *
     * Ví dụ:
     * - Input:  http://nxbgd.xyz/template/excel/template1.xlsx
     * - Output: C:\xampp7.4\htdocs\laravel-12\public\template\excel\template1.xlsx
     *
     * @param string $templatePathOrUrl URL đầy đủ, path tuyệt đối, hoặc path public (/template/excel/...)
     * @return string Đường dẫn tuyệt đối tới file trên disk
     * @throws Exception Khi path rỗng hoặc file không tồn tại
     */
    public function getTemplateFileAbsolutePath(string $templatePathOrUrl): string {
        $absolutePath = $this->resolveTemplateFileAbsolutePath($templatePathOrUrl);
        if ($absolutePath === null || !is_file($absolutePath)) {
            throw new Exception("Không tìm thấy file template: " . $templatePathOrUrl);
        }

        return $absolutePath;
    }
}
