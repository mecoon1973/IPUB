<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * Bảng nhóm cán bộ
 * @property int $_id
 * @property int $ID_CANBO
 * @property int $ID_NHOM
 * @property boolean $IsDeleted
 * @property boolean $InUsed
 *
 */
class Merge_nhom_canbo extends Model {
    protected $connection = "olm";

    protected $table = "ipub_nhom_canbo";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'ID_CANBO',
        'ID_NHOM',
        'IsDeleted',
        'InUsed',
    ];

    protected $attributes = [
        '_id' => 0,
        'ID_CANBO' => 0,
        'ID_NHOM' => 0,
        'IsDeleted' => false,
        'InUsed' => true,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
