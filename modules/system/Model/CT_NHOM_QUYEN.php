<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 *  @property int $_id
 * @property int $ID_NHOM
 * @property int $ID_QUYEN
 * @property boolean $IsDeleted
 * @property boolean $InUsed
 */
class CT_NHOM_QUYEN extends Model {
    protected $connection = "olm";

    protected $table = "ipub_nhom_quyen";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
    ];

    protected $attributes = [
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
