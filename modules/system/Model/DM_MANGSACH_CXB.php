<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $TenMang
 * @property string $MaMang
 * @property string $MoTa
 * @property bool $IsDeleted
 * @property bool $IsUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property datetime $EditedOn
 */
class DM_MANGSACH_CXB extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_mangsach_cxb";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'TenMang',
        'MaMang',
        'MoTa',
        'IsDeleted',
        'IsUsed',
        'DaGui',
        'KhoaGuiNhan',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'TenMang' => '',
        'MaMang' => '',
        'MoTa' => '',
        'IsDeleted' => false,
        'IsUsed' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
