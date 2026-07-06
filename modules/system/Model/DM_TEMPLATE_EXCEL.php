<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * Model DM_TEMPLATE_EXCEL
 * @property int $_id
 * @property string $key
 * @property string $path_file_template
 * @property bool $IsDeleted
 * @property DateTime $CreatedOn
 * @property DateTime $EditedOn
 * @property int $CreatedBy
 * @property int $EditedBy
 */
class DM_TEMPLATE_EXCEL extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_template_excel";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "key",
        "path_file_template",
        "IsDeleted",
        "CreatedOn",
        "EditedOn",
        "CreatedBy",
        "EditedBy",
    ];

    protected $attributes = [
        "_id" => false,
        "key" => "",
        "path_file_template" => "",
        "IsDeleted" => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
