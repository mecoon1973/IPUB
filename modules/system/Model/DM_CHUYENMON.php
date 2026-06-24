<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * @property int $_id
 * @property string $TenChuyenMon
 * @property string $MoTa
 * @property string $KhoaGuiNhan
 * @property bool $IsDeleted
 * @property bool $InUsed
 * @property bool $DaGui
 * @property int $CreatedBy
 * @property datetime $CreatedOn
 * @property int $EditedBy
 * @property datetime $EditedOn
 */
class DM_CHUYENMON extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_chuyenmon";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'TenChuyenMon',
        'MoTa',
        'KhoaGuiNhan',
        'IsDeleted',
        'InUsed',
        'DaGui',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'TenChuyenMon' => '',
        'MoTa' => '',
        'KhoaGuiNhan' => '',
        'IsDeleted' => false,
        'InUsed' => false,
        'DaGui' => false,
        'CreatedBy' => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
