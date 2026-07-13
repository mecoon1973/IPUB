<?php

namespace ExportFile\phpWord\Traits;

use ExportFile\phpWord\DocxTemplateEditor;
use InvalidArgumentException;

/**
 * Helper thay placeholder trong DOCX qua TemplateProcessor (XML-level).
 *
 * Class sử dụng trait cần implement {@see getTemplateEditor()}.
 */
trait HelperEditWord
{
    abstract public function getTemplateEditor(): DocxTemplateEditor;

    /**
     * Thay hàng loạt placeholder trong DOCX bằng giá trị tương ứng.
     *
     * @param array<string, mixed> $content Map placeholder → value
     */
    public function replateContent(array $content): void
    {
        foreach ($content as $placeholder => $value) {
            if (!is_string($placeholder) || $placeholder === '') {
                continue;
            }

            $this->replacePlaceholderValue($placeholder, $value);
        }
    }

    /**
     * Thay một placeholder trong toàn bộ document (body + header + footer).
     */
    public function replacePlaceholderValue(string $placeholder, mixed $value): void
    {
        if ($placeholder === '') {
            throw new InvalidArgumentException('Placeholder không được rỗng.');
        }

        $this->getTemplateEditor()->replaceLiteral(
            $placeholder,
            $this->normalizeReplacementValue($value)
        );
    }

    protected function normalizeReplacementValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
            return (string) $value;
        }

        return '';
    }
}
