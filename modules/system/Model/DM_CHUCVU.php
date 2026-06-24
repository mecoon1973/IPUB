<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * @property int $_id
 * @property string $MaChucVu
 * @property string $TenChucVu
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
class DM_CHUCVU extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_chucvu";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaChucVu',
        'TenChucVu',
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
        'MaChucVu' => '',
        'TenChucVu' => '',
        'MoTa' => '',
        'KhoaGuiNhan' => '',
        'IsDeleted' => false,
        'InUsed' => false,
        'DaGui' => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
