<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * @property int $_id
 * @property string $TenLoai
 * @property string $MangSach
 * @property string $ID_MangSach
 * @property int $Soluong
 * @property bool $IsDeleted
 * @property bool $InUsed
 */
class DM_LOAI_SNV extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_loai_snv";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "TenLoai",
        "MangSach",
        "ID_MangSach",
        "Soluong",
        "IsDeleted",
        "InUsed",
    ];

    protected $attributes = [
        "_id" => 0,
        "TenLoai" => "",
        "MangSach" => "",
        "ID_MangSach" => 0,
        "Soluong" => 0,
        "IsDeleted" => false,
        "InUsed" => true,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
