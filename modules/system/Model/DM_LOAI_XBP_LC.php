<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * loại xuất bản phẩm lưu chiểu
 * @property int $_id
 * @property string $TenLoai
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property bool $IsDeleted
 * @property bool $InUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 */
class DM_LOAI_XBP_LC extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_loai_xbp_lc";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'TenLoai',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'IsDeleted',
        'InUsed',
        'DaGui',
        'KhoaGuiNhan',
    ];

    protected $attributes = [
        '_id' => 0,
        'TenLoai' => '',
        'CreatedBy' => 0,
        'EditedBy' => 0,
        'IsDeleted' => false,
        'InUsed' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
