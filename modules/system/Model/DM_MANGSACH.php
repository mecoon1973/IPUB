<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * @property int $_id
 * @property string $MaMang
 * @property string $TenMang
 * @property string $MoTa
 * @property string $KiHieu
 * @property bool $IsDeleted
 * @property bool $IsUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 * @property string $Id_Childs
 * @property int $ParentID
 * @property int $VAT
 * @property int $iOrder
 * @property int $CreatedBy
 * @property datetime $CreatedOn
 * @property int $EditedBy
 * @property datetime $EditedOn
 */
class DM_MANGSACH extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_mangsach";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaMang',
        'TenMang',
        'MoTa',
        'KiHieu',
        'IsDeleted',
        'IsUsed',
        'DaGui',
        'KhoaGuiNhan',
        'Id_Childs',
        'ParentID',
        'VAT',
        'iOrder',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'MaMang' => '',
        'TenMang' => '',
        'MoTa' => '',
        'KiHieu' => '',
        'IsDeleted' => false,
        'IsUsed' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'Id_Childs' => '',
        'ParentID' => 0,
        'VAT' => 0,
        'iOrder' => 0,
        'CreatedBy' => 0,
        'CreatedOn' => null,
        'EditedBy' => 0,
        'EditedOn' => null,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
