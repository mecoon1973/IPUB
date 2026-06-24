<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 *
 */
class CT_FUNCTION_QUYEN extends Model {
    protected $connection = "olm";

    protected $table = "ipub_function_quyen";
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
