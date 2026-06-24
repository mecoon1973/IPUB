<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * DM_TUSACH: DM_TUSACH
 * @property int $_id
 * @property string $MaTuSach
 * @property string $TenTuSach
 * @property string $MoTa
 * @property string $KhoaGuiNhan
 * @property bool $IsDeleted
 * @property bool $InUsed
 * @property bool $DaGui
 * @property int $CreatedBy
 * @property datetime $CreatedOn
 * @property int $EditedBy
 * @property datetime $EditedOn
 *
 */
class DM_TUSACH extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_tusach";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaTuSach',
        'TenTuSach',
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
        'MaTuSach' => '',
        'TenTuSach' => '',
        'MoTa' => '',
        'KhoaGuiNhan' => '',
        'IsDeleted' => false,
        'InUsed' => false,
        'DaGui' => false,
        'CreatedBy' => 0,
        'EditedBy' => 0,
        'CreatedOn' => null,
        'EditedOn' => null,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
