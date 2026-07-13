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
     * Thay hàng loạt placeholder trong DOCX bằng giá trị tương ứng (text thuần).
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
     * Thay hàng loạt placeholder bằng HTML (giữ bold/italic/list/indent/numbering).
     *
     * Placeholder HTML nên đứng một mình trong một đoạn Word.
     *
     * @param array<string, mixed> $content Map placeholder → HTML string
     */
    public function replateHtmlContent(array $content): void
    {
        foreach ($content as $placeholder => $value) {
            if (!is_string($placeholder) || $placeholder === '') {
                continue;
            }

            $this->replaceHtmlContent($placeholder, $value);
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

    /**
     * Thay một placeholder bằng HTML block (OOXML), không escape thẻ.
     */
    public function replaceHtmlContent(string $placeholder, mixed $value): void
    {
        if ($placeholder === '') {
            throw new InvalidArgumentException('Placeholder không được rỗng.');
        }

        $this->getTemplateEditor()->replaceHtmlBlock(
            $placeholder,
            $this->normalizeReplacementValue($value)
        );
    }

    /**
     * Nhân bản hàng bảng chứa placeholder (giống Excel duplicateRowCellsBelow).
     *
     * Placeholder trong DOCX phải nằm trong <w:tr>. Hàng gốc + $duplicateCount bản sao.
     *
     * @param list<string> $columnKeys     Key từ map_replate, vd: ["!a!", "!b!", "!c!"]
     * @param int          $duplicateCount Số hàng chèn thêm (mảng 10 phần tử → 9)
     */
    public function duplicateTableRowBelow(array $columnKeys, int $duplicateCount): void
    {
        if ($columnKeys === []) {
            throw new InvalidArgumentException('columnKeys không được rỗng.');
        }

        $anchor = (string) $columnKeys[0];
        $this->getTemplateEditor()->duplicateTableRowBelow(
            $anchor,
            $duplicateCount,
            array_values(array_map('strval', $columnKeys))
        );
    }

    /**
     * Điền ma trận dữ liệu vào các hàng đã nhân bản (giống Excel fillDuplicatedRowValues).
     *
     * @param list<string>                  $columnKeys
     * @param array<int, array<int, mixed>> $rowValuesMatrix
     */
    public function fillDuplicatedRowValues(array $columnKeys, array $rowValuesMatrix): void
    {
        $this->getTemplateEditor()->fillDuplicatedRowValues(
            array_values(array_map('strval', $columnKeys)),
            $rowValuesMatrix
        );
    }

    /**
     * Gộp duplicate + fill cho TYPE_LOOP trên DOCX.
     *
     * @param list<string>                  $columnKeys
     * @param array<int, array<int, mixed>> $rowValuesMatrix
     */
    public function applyLoopRows(array $columnKeys, array $rowValuesMatrix): void
    {
        $this->getTemplateEditor()->applyLoopRows(
            array_values(array_map('strval', $columnKeys)),
            $rowValuesMatrix
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
