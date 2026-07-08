<?php

namespace Core\Object;

use Core\Facade\Helper;

/**
 * Thông tin phân trang
 *
 * @property array $pagi_number Mảng các số trang
 * @property int $last Số trang cuối cùng
 * @property int $limit Số lượng bản ghi trên mỗi trang
 * @property int $current_page Số trang hiện tại
 * @property int $total Tổng số bản ghi
 * @property string $query Query param ?key=value&...
 * @property string $route Đường dẫn hiện tại
 * @property string $startCursor Cursor bắt đầu
 * @property string $endCursor Cursor cuối
 * @property bool $hasNextPage Có trang tiếp theo không
 * @property bool $hasPreviousPage Có trang trước không
 */
class PagiInfo {
    public $pagi_number = [];
    public $last = 1;
    public $limit = 0;
    public int $current_page = 1;
    public $total = 1;
    public $query = "";
    public $route = "";
    public $startCursor = null;
    public $endCursor = null;
    public $hasNextPage = false;
    public $hasPreviousPage = false;
    public $cursor_based = false;


    public function __construct($input = [])
    {
        // Nếu truyền vào flag 'cursor_based' thì dùng kiểu cursor
        if (isset($input['cursor_based'])) {
            $this->cursor_based = $input['cursor_based'];
        }

        // Gán các thuộc tính theo input truyền vào
        foreach ($input as $key => $value) {
            $this->$key = $value;
        }

        // Nếu route chưa được set, lấy đường dẫn hiện tại không bao gồm tham số page, trang-truoc, trang-sau
        if(!$this->route) {
            $this->route = '/'.Helper::getPathWithoutPage();
        }

        // Nếu query chưa được set, build từ request query parameters.
        if (!$this->query) {
            $query = [];
            $queryParams = request()->query();
            $cursorId = config("settings.key_cursor_id", "");
            $cursorSortPrefix = config("settings.prefix_key_cursor_sort", "");

            try {
                foreach ($queryParams as $key => $value) {
                    if ($key !== $cursorId && strpos($key, $cursorSortPrefix) !== 0) {
                        if (is_array($value) || is_object($value)) {
                            $valueArray = is_object($value) ? (array)$value : $value;
                            foreach ($valueArray as $v) {
                                if (is_array($v) || is_object($v)) {
                                    $subArray = is_object($v) ? (array)$v : $v;
                                    foreach ($subArray as $subValue) {
                                        $finalValue = is_object($subValue) ? (string)$subValue : $subValue;
                                        $query[] = $key . "=" . urlencode($finalValue);
                                    }
                                } else {
                                    $finalValue = is_object($v) ? (string)$v : $v;
                                    $query[] = $key . "=" . urlencode($finalValue);
                                }
                            }
                        } else {
                            $finalValue = is_object($value) ? (string)$value : $value;
                            $query[] = $key . "=" . urlencode($finalValue);
                        }
                    }
                }
            } catch (\Exception $e) {
            }

            if (!empty($query)) {
                $this->query = "?" . implode("&", $query);
            }
        }

        $forward = request()->route('cursordirection') == 'trang-truoc' ? false : true;

        // Nếu là trang tiếp theo thì set hasPreviousPage và hasNextPage
        if($forward) {
            $this->hasPreviousPage = true;
        } else {
            $this->hasNextPage = true;
        }

        // Nếu là trang đầu tiên thì set các thuộc tính hasPreviousPage và hasNextPage
        if (core_cursor_first_page()) {
            $this->hasPreviousPage = false;
        }

    }
}
