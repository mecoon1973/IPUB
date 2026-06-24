<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * @property int $_id
 * @property int $SoLuong
 * @property int $ID_LOAI_SNV
 * @property int $ID_DV_SNV
 * @property bool $InUsed
 * @property bool $IsDeleted
 */
class CT_DV_LOAI_SNV extends Model {
    protected $connection = "olm";

    protected $table = "ipub_ct_dv_loai_snv";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "SoLuong",
        "ID_LOAI_SNV",
        "ID_DV_SNV",
        "InUsed",
        "IsDeleted",
    ];

    protected $attributes = [
        "_id" => 0,
        "SoLuong" => 0,
        "ID_LOAI_SNV" => 0,
        "ID_DV_SNV" => 0,
        "InUsed" => true,
        "IsDeleted" => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
