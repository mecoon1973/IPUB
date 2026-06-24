<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
/**
 *  @property int $_id
 *  @property int $ID_PHIEU_DK_DETAI
 *  @property int $ID_CANBO
 *  @property bool $InUsed
 *  @property bool $IsDeleted
 */
class CT_PhieuDkDetai_BTV extends Model {
    protected $connection = "olm";

    protected $table = "ipub_ct_phieu_dk_detai_bientapvien";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "ID_PHIEU_DK_DETAI",
        "ID_CANBO",
        "InUsed",
        "IsDeleted",
    ];

    protected $attributes = [
        "_id" => 0,
        "ID_PHIEU_DK_DETAI" => 0,
        "ID_CANBO" => 0,
        "InUsed" => false,
        "IsDeleted" => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
