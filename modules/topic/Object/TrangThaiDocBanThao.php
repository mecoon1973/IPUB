<?php

namespace Modules\Topic\Object;

/**
 * Trạng thái đọc duyệt bản thảo trên ipub_dm_sach.TrangThaiDocBanThao
 */
final class TrangThaiDocBanThao
{
    public const CHUA_DOC_DUYET = 0;
    public const DANG_DOC_DUYET = 1;
    public const DA_DOC_DUYET = 2;

    public static function ketLuanPheDuyetDiIn(): array
    {
        return [
            self::CHUA_DOC_DUYET,
            self::DA_DOC_DUYET,
        ];
    }

    public static function all(): array
    {
        return [
            self::CHUA_DOC_DUYET,
            self::DANG_DOC_DUYET,
            self::DA_DOC_DUYET,
        ];
    }

    public static function label(int $value): string
    {
        $map = [
            self::CHUA_DOC_DUYET => 'Chưa đọc duyệt',
            self::DANG_DOC_DUYET => 'Đang đọc duyệt',
            self::DA_DOC_DUYET => 'Đã đọc duyệt',
        ];

        return $map[$value] ?? (string) $value;
    }

    private function __construct() {}
}
