<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * @property int $_id
 * @property string $TenTrangThai
 * @property int $MaTrangThai
 * @property bool $DaGui
 * @property int $Order
 */
class DM_TRANG_THAI extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_trangthai";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = false;

    protected $fillable = [
        "_id",
        "TenTrangThai",
        "MaTrangThai",
        "DaGui",
        "Order",
    ];

    protected $attributes = [
        "_id" => 0,
        "TenTrangThai" => "",
        "MaTrangThai" => 0,
        "DaGui" => false,
        "Order" => 0,
    ];

    protected $casts = [
    ];

    protected $customCasts = [];
}
