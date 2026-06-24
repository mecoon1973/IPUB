<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $ConfigName
 * @property string $ConfigNotes
 * @property string $ConfigValue
 * @property string $CreateBy
 * @property DateTime $CreatedOn
 * @property string $EditedBy
 * @property DateTime $EditedOn
 * @property bool $AllowDelete
 * @property bool $AllowEdit
 * @property bool $InUsed
 * @property bool $DaGui
 */
class DM_BIEN_MOI_TRUONG extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_bien_moi_truong";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "ConfigName",
        "ConfigNotes",
        "ConfigValue",
        "CreateBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
        "AllowDelete",
        "AllowEdit",
        "InUsed",
        "DaGui",
    ];

    protected $attributes = [
        "_id" => 0,
        "ConfigName" => "",
        "ConfigNotes" => "",
        "ConfigValue" => "",
        "CreateBy" => 0,
        "EditedBy" => 0,
        "AllowDelete" => true,
        "AllowEdit" => true,
        "InUsed" => true,
        "DaGui" => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
