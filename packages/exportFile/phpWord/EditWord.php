<?php

namespace ExportFile\phpWord;

use ExportFile\phpWord\Traits\HelperEditWord;
use RuntimeException;

/**
 * Chỉnh sửa file Word có sẵn (.docx) bằng TemplateProcessor (thao tác XML trong ZIP).
 *
 * Luồng sử dụng cơ bản:
 *   1. new EditWord($path)     — clone file .docx vào editor
 *   2. replateContent($map)     — thay placeholder bằng value
 *   3. save($outputPath)        — ghi file mới
 *
 * Lưu ý:
 * - Không dùng IOFactory::load()/PhpWord object model vì reader Word2007 không
 *   parse đủ OpenXML (DrawingML/VML textbox…) — load rồi save sẽ mất checkbox/textbox.
 * - TemplateProcessor giữ nguyên XML gốc; chỉ thay chuỗi text.
 * - Placeholder dạng !Name! bị Word tách run sẽ được nối lại trước khi replace.
 */
class EditWord
{
    use HelperEditWord;

    protected string $originalPath;

    protected DocxTemplateEditor $templateEditor;

    public function __construct(string $path)
    {
        $this->originalPath = core_normalize_path($path);
        assert_file_exists($this->originalPath, 'docx');
        $this->templateEditor = $this->loadTemplateEditor($this->originalPath);
    }

    public function getOriginalPath(): string
    {
        return $this->originalPath;
    }

    public function getTemplateEditor(): DocxTemplateEditor
    {
        return $this->templateEditor;
    }

    protected function loadTemplateEditor(string $path): DocxTemplateEditor
    {
        try {
            return new DocxTemplateEditor($path);
        } catch (\Throwable $exception) {
            throw new RuntimeException(
                'Không thể đọc file Word: ' . $path . ' — ' . $exception->getMessage(),
                0,
                $exception
            );
        }
    }

    public function save(string $path): void
    {
        $this->templateEditor->saveAs($path);
    }
}
