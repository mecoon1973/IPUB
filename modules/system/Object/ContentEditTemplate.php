<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;
use InvalidArgumentException;

/**
 * Một nhóm cấu hình chèn nội dung vào template Excel.
 *
 * @see resources/ts/modules/system/type/TemplateExport.ts — ContentEditTemplate
 *
 * @property string $type      ContentEditTemplateType::TEXT|ContentEditTemplateType::LOOP
 * @property string $key_data  Đường dẫn dữ liệu gốc (bắt buộc khi type = loop)
 * @property array<string, ContentReplaceTemplate> $map_replate Map placeholder/ô → value + callback
 */
class ContentEditTemplate extends BaseObject
{
    /** Chèn text (thay placeholder đơn) */
    public const TYPE_TEXT = 'text';

    /** Chèn vòng lặp (nhân bản hàng theo mảng) */
    public const TYPE_LOOP = 'loop';

    public string $type = self::TYPE_TEXT;

    public string $key_data = '';

    /**
     * @var array<string, array{value: string, callback: string}>
     */
    public array $map_replate = [];

    /**
     * @param array<string, mixed> $input
     */
    public function __construct($input = [])
    {
        if (isset($input['map_replate']) && is_array($input['map_replate'])) {
            $input['map_replate'] = self::normalizeMapReplate($input['map_replate']);
        }

        parent::__construct($input);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $type = (string) ($data['type'] ?? self::TYPE_TEXT);
        if (!in_array($type, [self::TYPE_TEXT, self::TYPE_LOOP], true)) {
            throw new InvalidArgumentException('content_edit.type không hợp lệ: ' . $type);
        }

        return new self([
            'type' => $type,
            'key_data' => (string) ($data['key_data'] ?? ''),
            'map_replate' => is_array($data['map_replate'] ?? null) ? $data['map_replate'] : [],
        ]);
    }

    /**
     * @param array<int, array<string, mixed>> $items
     *
     * @return array<int, self>
     */
    public static function listFromArray(array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $result[] = self::fromArray($item);
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $mapReplate
     *
     * @return array<string, array{value: string, callback: string}>
     */
    public static function normalizeMapReplate(array $mapReplate): array
    {
        $normalized = [];

        foreach ($mapReplate as $placeholder => $replaceConfig) {
            if (!is_string($placeholder) || $placeholder === '') {
                continue;
            }

            if (is_array($replaceConfig)) {
                $normalized[$placeholder] = ContentReplaceTemplate::fromArray($replaceConfig)->toStorageArray();
                continue;
            }

            // Hỗ trợ legacy: value là string thuần
            $normalized[$placeholder] = [
                'value' => (string) $replaceConfig,
                'callback' => '',
            ];
        }

        return $normalized;
    }

    /**
     * @return array{type: string, key_data: string, map_replate: array<string, array{value: string, callback: string}>}
     */
    public function toStorageArray(): array
    {
        return [
            'type' => $this->type,
            'key_data' => $this->key_data,
            'map_replate' => $this->map_replate,
        ];
    }

    /**
     * @param array<int, self> $items
     *
     * @return array<int, array{type: string, key_data: string, map_replate: array<string, array{value: string, callback: string}>}>
     */
    public static function listToStorageArray(array $items): array
    {
        return array_map(
            static fn (self $item) => $item->toStorageArray(),
            $items
        );
    }

    public function getDataText(array $data, ?bool $html = false): array
    {
        if ($this->type !== self::TYPE_TEXT) {
            throw new InvalidArgumentException('type phải là ' . self::TYPE_TEXT . ', nhận được: ' . $this->type);
        }

        $result = [];

        foreach ($this->map_replate as $placeholder => $replaceConfig) {
            $config = ContentReplaceTemplate::fromArray(
                is_array($replaceConfig) ? $replaceConfig : ['value' => (string) $replaceConfig]
            );

            $value = data_get($data, $config->value, '');
            if ($config->callback !== '') {
                if(function_exists($config->callback)){
                    if($html && $config->callback == "core_normalize_html_to_string"){
                        // mục đích khi lấy dữ liệu cho file .html thì cần giữ lại các thẻ html
                    }else{
                        $value = call_user_func($config->callback, $value);
                    }
                }
            }

            $result[$placeholder] = $value;
        }
        return $result;
    }

    /**
     * Ma trận dữ liệu vòng lặp — mỗi hàng là 1 phần tử, mỗi cột theo thứ tự map_replate.
     *
     * Ví dụ với key_data = "listItem" và map_replate { A9: {value: "a"}, B9: {value: "b"} }:
     * [
     *   ["a0", "b0"],
     *   ["a1", "b1"],
     * ]
     *
     * @param array<string, mixed> $data Dataset gốc (vd: ["listItem" => [...]])
     *
     * @return array<int, array<int, mixed>>
     */
    public function getDataLoop(array $data): array
    {
        if ($this->type !== self::TYPE_LOOP) {
            throw new InvalidArgumentException('type phải là ' . self::TYPE_LOOP . ', nhận được: ' . $this->type);
        }

        if (trim($this->key_data) === '') {
            throw new InvalidArgumentException('key_data không được rỗng khi type = loop');
        }

        $list = data_get($data, $this->key_data, []);

        if (!is_array($list) || $list === []) {
            return [];
        }

        $matrixContent = [];

        foreach ($list as $index => $item) {
            $row = [];

            foreach ($this->map_replate as $replaceConfig) {
                $config = ContentReplaceTemplate::fromArray(
                    is_array($replaceConfig) ? $replaceConfig : ['value' => (string) $replaceConfig]
                );

                if ($config->value === 'index') {
                    $row[] = $index + 1;
                    continue;
                }

                $value = $this->resolveLoopItemValue($item, $config->value);

                if ($config->callback !== '') {
                    $value = call_user_func($config->callback, $value);
                }

                $row[] = $value;
            }

            $matrixContent[] = $row;
        }
        return $matrixContent;
    }

    /**
     * Danh sách key cột (placeholder / ô Excel) theo thứ tự map_replate.
     *
     * @return array<int, string>
     */
    public function getLoopColumnKeys(): array
    {
        return array_keys($this->map_replate);
    }

    /**
     * Lấy giá trị field trong từng phần tử vòng lặp (đường dẫn tương đối trong $item).
     */
    private function resolveLoopItemValue(mixed $item, string $fieldPath): mixed
    {
        $fieldPath = trim($fieldPath);

        if (str_starts_with($fieldPath, 'item.')) {
            $fieldPath = substr($fieldPath, 5);
        }

        return data_get($item, $fieldPath, '');
    }

}
