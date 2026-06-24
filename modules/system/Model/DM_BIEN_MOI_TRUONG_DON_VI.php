<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 *
 */
class DM_BIEN_MOI_TRUONG_DON_VI extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_bien_moi_truong_don_vi";
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