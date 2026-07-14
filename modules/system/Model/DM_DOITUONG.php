<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $MaDoiTuong
 * @property string $TenDoiTuong
 * @property string $MoTa
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property Datetime $EditedOn
 * @property boolean $IsDeleted
 * @property boolean $InUsed
 * @property string $KhoaGuiNhan
 * @property string $KiHieu
 * @property string $type
 */
class DM_DOITUONG extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_dt_sudung";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "MaDoiTuong",
        "TenDoiTuong",
        "MoTa",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
        "IsDeleted",
        "InUsed",
        "KhoaGuiNhan",
        "KiHieu",
        "MaDoiTuong",
        "type",
    ];

    protected $attributes = [
        "_id" => 0,
        "MaDoiTuong" => "",
        "TenDoiTuong" => "",
        "MoTa" => "",
        "CreatedBy" => 0,
        "CreatedOn" => null,
        "EditedBy" => 0,
        "EditedOn" => null,
        "IsDeleted" => false,
        "InUsed" => true,
        "KhoaGuiNhan" => "",
        "KiHieu" => "",
        "type" => "",
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
