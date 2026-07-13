<?php

use Carbon\Carbon;

if(!function_exists("regexRoute")) {
    function regexRoute($type) {
        switch ($type) {
            case 'alias':
                return "[a-zA-Z0-9-.]+[0-9][0-9]*";
                break;
            case 'page':
                return "page-[1-9][0-9]*";
                break;
            case 'cursor':
                return "[0-9]+";
                break;
            default:
                return "";
                break;
        }
    }
}

if (!function_exists('core_cursor_first_page')) {
    /**
     * Kiểm tra xem có phải là trang đầu tiên hay không
     * @return boolean
     */
    function core_cursor_first_page() {
        if (!request()->has(config("settings.key_cursor_id", ""))) {
            return true;
        }
        return false;
    }
}

if (!function_exists('core_decode_cursor')) {
    /**
     * Giải mã cursor
     * @param string|null $cursor Cursor
     * @param array $default Giá trị mặc định
     * @return array Dữ liệu offset
     */
    function core_decode_cursor($cursor, $default = []) {
        if ($cursor == null) {
            return $default;
        }

        try {
            $offset = substr(base64_decode($cursor), strlen(config("settings.prefix_cursor_pagination", "")));
            $decodedOffset = unserialize($offset);
            return is_array($decodedOffset) ? $decodedOffset : $default;
        } catch (\Exception $e) {
            return $default; //fallback
        }
    }
}

if (!function_exists('core_create_cursor')) {
    /**
     * Tạo cursor cho phân trang cursor-based
     * @param array $item Dữ liệu item
     * @param string|null $sort_field Tên field sắp xếp
     * @return string Cursor
     */
    function core_create_cursor($item, $sort_field = null) {
        $offset = [
            "_id" => $item['_id'],
        ];
        if (!is_null($sort_field) && isset($item[$sort_field])) {
            $offset[$sort_field] = $item[$sort_field];
        }

        return core_init_cursor($offset);
    }
}

if (!function_exists('core_init_cursor')) {
    /**
     * Khởi tạo cursor
     * @param array $offset Dữ liệu offset
     * @return string Cursor
     */
    function core_init_cursor($offset) {
        return base64_encode(config("settings.prefix_cursor_pagination") . serialize($offset));
    }
}

if (!function_exists('core_iso8601_to_unix_int')) {
    /**
     * Chuyển chuỗi ISO-8601 (ví dụ 2009-04-14T09:32:00.000+00:00) sang Unix timestamp — số giây kiểu int.
     *
     * @param  string|null  $iso8601
     * @return int|null null nếu chuỗi rỗng hoặc không parse được
     */
    function core_iso8601_to_unix_int(?string $iso8601): ?int {
        if ($iso8601 === null || $iso8601 === '') {
            return null;
        }

        try {
            return (int) \Illuminate\Support\Carbon::parse($iso8601)->timestamp;
        } catch (\Throwable $th) {
            return null;
        }
    }
}

if (!function_exists('core_date_to_iso8601_utc_offset')) {
    /**
     * Chuỗi ISO-8601 UTC có millis + offset +00:00 (giữ cùng “kiểu” với client gửi xuống).
     *
     * @param  string|int  $value  Chuỗi datetime hoặc Unix timestamp (giây)
     */
    function core_date_to_iso8601_utc_offset(string|int|null $value): string|null
    {
        if ($value === '' || $value === null) {
            return null;
        }

        if (is_numeric($value)) {
            if ((int) $value === 0) {
                return null;
            } else {
                return Carbon::createFromTimestampUTC((int) $value)->utc()->format('Y-m-d\TH:i:s.vP');
            }
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return null;
            }
            // JS Date#toString(): "... GMT+0700 (Indochina Time)"
            $cleaned = preg_replace('/\s*\([^)]*\)\s*$/', '', $trimmed) ?? $trimmed;
            try {
                return Carbon::parse($cleaned)->utc()->format('Y-m-d\TH:i:s.vP');
            } catch (\Throwable $th) {
                try {
                    return Carbon::parse($trimmed)->utc()->format('Y-m-d\TH:i:s.vP');
                } catch (\Throwable $th2) {
                    return null;
                }
            }
        }
        return null;
    }
}

if(!function_exists('convert_array_to_hashtable')){
    /**Chuyển đổi array thành hashtable
     *
     * @param array $list (lưu ý khi sử dụng findAll() thì cần dùng thêm toArray() để lấy ra array từ object để sử dụng hàm này)
     * @param string $keyField (lấy giá trị của field để làm key của dict)
     * @param array $valueField (chỉ lấy các trường dữ liệu cần thiết)
     * @return array<[$keyField], array<key, value>>
     */
    function convert_array_to_hashtable(array $list, string $keyField, array $valueField = [], bool $emptyValue = false){
        $hashtable = [];
        foreach($list as $item){
            if(empty($item[$keyField]) && !$emptyValue) continue;
            if(count($valueField) == 0){
                $hashtable[$item[$keyField]][] = $item;
            } else {
                $itemValue = [];
                foreach($valueField as $field){
                    $itemValue[$field] = $item[$field] ?? "";
                }
                $hashtable[$item[$keyField]][] = $itemValue;
            }
        }
        return $hashtable;
    }
}

if(!function_exists('get_field_from_list')){
    /**
     * Lấy ra các giá trị của field từ list
     * @param array $list (lưu ý khi sử dụng findAll() thì cần dùng thêm toArray() để lấy ra array từ object để sử dụng hàm này)
     * @param string $keyField (lấy giá trị của field để làm key của array)
     * @param bool $checkUnique (kiểm tra xem có phải là unique hay không)
     * @return array<keyField> 1 mảng keyField của từng item trong list
     */

    function get_field_from_list(array $list, string $keyField, bool $checkUnique = false){
        $listField = [];
        $seen = [];
        foreach($list as $item){
            if(empty($item[$keyField])) continue;
            if($checkUnique){
                if(!isset($seen[$item[$keyField]])){
                    $seen[$item[$keyField]] = true;
                    $listField[] = $item[$keyField];
                }
            }else{
                $listField[] = $item[$keyField];
            }
        }
        return $listField;
    }
}

if(!function_exists('core_normalize_type_value')){
    /**
     * Chuẩn hóa type value
     * @param string $type
     * @param string $value
     * @return string
     */
    function core_normalize_type_value(string $type, $value){

        switch($type){
            case "boolean":
            case "bool":
                return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            case "integer":
            case "int":
                if(is_null($value)) return null;
                return (int)$value;
            case "float":
            case "double":
                if(is_null($value)) return 0.0;
                return (float)$value;
            case "string":
                if(is_null($value)) return "";
                return (string)$value;
            case "array":
                if(is_null($value)) return [];
                return (array)$value;
            case "date":
            case "datetime":
                if(is_null($value)) return null;
                return core_date_to_iso8601_utc_offset($value);
            case "array|datetime":
                if (is_array($value)) {
                    if(is_null($value)) return [];
                    return array_map(
                        static fn($item) => core_date_to_iso8601_utc_offset($item),
                        $value
                    );
                }
                return core_date_to_iso8601_utc_offset($value);
            default:
                return $value;
        }
    }
}
if(!function_exists('assert_file_exists')){
    /**
     * Kiểm tra xem file có tồn tại và có định dạng phù hợp không
     * @param string $path Đường dẫn file
     * @param string|null $extension Định dạng file
     * @throws InvalidArgumentException Nếu file không tồn tại hoặc định dạng không phù hợp
     * @return void
     */
    function assert_file_exists(string $path, ?string $extension): void
    {
        if (!is_file($path)) {
            throw new InvalidArgumentException("File không tồn tại: {$path}");
        }

        if($extension){
            if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== $extension) {
                throw new InvalidArgumentException('File phải có định dạng .' . $extension);
            }
        }
    }
}

if(!function_exists('assert_file_exists')){
    /**
     * Kiểm tra xem file có tồn tại và có định dạng phù hợp không
     * @param string $path Đường dẫn file
     * @param string|null $extension Định dạng file
     * @throws InvalidArgumentException Nếu file không tồn tại hoặc định dạng không phù hợp
     * @return void
     */
    function assert_file_exists(string $path, ?string $extension): void
    {
        if (!is_file($path)) {
            throw new InvalidArgumentException("File không tồn tại: {$path}");
        }

        if($extension){
            if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== $extension) {
                throw new InvalidArgumentException('File phải có định dạng .' . $extension);
            }
        }
    }
}
if(!function_exists('core_normalize_path')){
    /**
     * Chuẩn hóa đường dẫn file
     * @param string $path Đường dẫn file
     * @return string Đường dẫn file chuẩn hóa
     */
    function core_normalize_path(string $path): string
    {
        $realPath = realpath($path);
        return $realPath !== false ? $realPath : $path;
    }

}

if (!function_exists('core_normalize_html_to_string')) {
    /**
     * Chuyển HTML thành chuỗi plain text (decode entity, bỏ thẻ, giữ xuống dòng, ol/ul).
     *
     * @param string $html HTML đầu vào
     * @return string Chuỗi text đã chuẩn hóa
     */
    function core_normalize_html_to_string(string $html): string
    {
        return \Core\Utility\HtmlToTextNormalizer::toString($html);
    }
}
if(!function_exists('core_normalize_date_time_to_string')){
    /**
     * Chuẩn hóa datetime
     * @param string|int|\DateTimeInterface $dateTime Chuỗi datetime hoặc Unix timestamp (giây)
     * @return string Chuỗi datetime chuẩn hóa "ngày 2 tháng 7 năm 2026"
     */
    function core_normalize_date_time_to_string(string|int|\DateTimeInterface $dateTime): string
    {
        if ($dateTime instanceof \DateTimeInterface) {
            $carbon = Carbon::instance($dateTime);
        } elseif (is_int($dateTime)) {
            $carbon = Carbon::createFromTimestamp($dateTime);
        } else {
            $trimmed = trim($dateTime);

            if ($trimmed === '') {
                return '';
            }

            if (ctype_digit($trimmed)) {
                $carbon = Carbon::createFromTimestamp((int) $trimmed);
            } else {
                $carbon = Carbon::parse($trimmed);
            }
        }

        if (!$carbon->isValid()) {
            throw new InvalidArgumentException('Datetime không hợp lệ: ' . (string) $dateTime);
        }

        return sprintf(
            'ngày %d tháng %d năm %d',
            (int) $carbon->format('j'),
            (int) $carbon->format('n'),
            (int) $carbon->format('Y')
        );
    }
}
