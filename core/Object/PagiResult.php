<?php

namespace Core\Object;

/**
 * Output cho phân trang
 * 
 * @template Type
 * @property iterable<Type> $list Danh sách dữ liệu phân trang
 * @property PagiInfo $pagi_info Thông tin phân trang (có thể chứa paginate thông thường hoặc cursor)
 */
class PagiResult {
    /**
     * Danh sách dữ liệu phân trang
     * @var iterable<Type>
     */
    public $list;
    
    /**
     * Thông tin phân trang (có thể chứa paginate thông thường hoặc cursor)
     * @var PagiInfo
     */
    public $pagi_info;

    public function __construct($list, PagiInfo $pagi_info = null) {
        $this->list = $list;
        $this->pagi_info = !is_null($pagi_info) ? $pagi_info : new PagiInfo;
    }
}