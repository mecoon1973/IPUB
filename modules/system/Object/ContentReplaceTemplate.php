<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;

/**
 * Cấu hình thay thế cho một placeholder / ô Excel.
 *
 * @see resources/ts/modules/system/type/TemplateExcel.ts — ContentReplaceTemplate
 *
 * @property string $value    Đường dẫn dữ liệu cần thay thế
 * @property string $callback Hàm callback xử lý sau khi lấy value (tuỳ chọn)
 */
class ContentReplaceTemplate extends BaseObject
{
    public string $value = '';

    public string $callback = '';

    /**
     * @param array<string, mixed> $input
     */
    public function __construct($input = [])
    {
        parent::__construct($input);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self([
            'value' => (string) ($data['value'] ?? ''),
            'callback' => (string) ($data['callback'] ?? ''),
        ]);
    }

    /**
     * @return array{value: string, callback: string}
     */
    public function toStorageArray(): array
    {
        return [
            'value' => $this->value,
            'callback' => $this->callback,
        ];
    }
}
