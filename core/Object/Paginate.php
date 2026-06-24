<?php

namespace Core\Object;

/**
 * Input cho phân trang
 * 
 * @property string $page Trang hiện tại
 * @property array $conditions Điều kiện tìm kiếm
 * @property array $countConditions Đôi khi cần tính tổng số bản ghi với điều kiện khác với điều kiện tìm kiếm (lý do hiệu năng)
 * @property array $fields Các trường cần lấy
 * @property int $limit Số lượng bản ghi trên mỗi trang
 * @property int $skip Số bản ghi bỏ qua
 * @property array $sorted Sắp xếp theo trường và hướng
 * @property bool $useCursor Nếu giá trị này được đặt thành true thì sẽ dùng cursor-based, false: skip-based (mặc định)
 * @property array $cursorSorted Sắp xếp theo trường và hướng cho cursor-based
 * @property string $cursor Cursor
 * @property bool $forward Nếu giá trị này được đặt thành true thì sẽ dùng forward, false: backward
 * @property array $loadRelations Các mối quan hệ cần load
 */
class Paginate extends BaseObject
{
    public $page = 'page-1';
    public $conditions = [];
    public $countConditions = [];
    public $fields = [];
    public $limit = 10;
    public $skip = 0;
    public $sorted = ['_id' => -1];
    public $useCursor = false;
    public $cursorSorted = ['_id' => 'desc'];
    public $cursor = null;
    public $forward = true;
    public $loadRelations = [];
}