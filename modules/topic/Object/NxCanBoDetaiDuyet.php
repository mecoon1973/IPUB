<?php

namespace Modules\Topic\Object;

/** Giá trị trường Duyet trong ipub_nx_canbo_detai */
final class NxCanBoDetaiDuyet
{
    public const CHUA_XET = 0;
    public const DUYET = 1;
    public const TRA_LAI = 2;

    public static function labels(): array
    {
        return [
            self::CHUA_XET => 'Chưa xử lý',
            self::DUYET => 'Duyệt',
            self::TRA_LAI => 'Trả lại',
        ];
    }

    private function __construct() {}
}
