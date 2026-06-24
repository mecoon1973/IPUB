<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * @property int $id
 * @property int $ID_CanBo
 * @property int $ID_QUYEN
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 *
 */
class CT_CANBO_QUYEN extends Model {
    protected $connection = "olm";

    protected $table = "ipub_can_bo_quyen";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        'id',
        'ID_CanBo',
        'ID_QUYEN',
        'InUsed',
        'IsDeleted',
    ];

    protected $attributes = [
        'id' => 0,
        'ID_CanBo' => 0,
        'ID_QUYEN' => 0,
        'InUsed' => true,
        'IsDeleted' => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
