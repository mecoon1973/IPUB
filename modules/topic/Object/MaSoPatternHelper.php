<?php

namespace Modules\Topic\Object;

/** Chuyển pattern tìm mã số dạng ???G??6 hoặc *G??6 sang biểu thức regex MongoDB */
final class MaSoPatternHelper
{
    public static function hasWildcard(string $pattern): bool
    {
        return strpbrk($pattern, '?*') !== false;
    }

    public static function toRegex(string $pattern): string
    {
        $chars = preg_split('//u', $pattern, -1, PREG_SPLIT_NO_EMPTY);
        $regex = '';
        foreach ($chars as $char) {
            if ($char === '?') {
                $regex .= '.';
                continue;
            }
            if ($char === '*') {
                $regex .= '.*';
                continue;
            }
            $regex .= preg_quote($char, '/');
        }

        return '^' . $regex . '$';
    }

    private function __construct() {}
}
