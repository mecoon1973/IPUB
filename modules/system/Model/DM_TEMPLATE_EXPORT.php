<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;
use Modules\System\Object\ContentEditTemplate;

/**
 * Model DM_TEMPLATE_EXPORT
 * @property int $_id
 * @property string $key
 * @property string $name
 * @property string $path_file_template // url path file
 * @property string $path_file_template_doc // url path file Word (.doc/.docx)
 * @property ContentEditTemplate[] $content_edit // cấu hình chèn nội dung (text / loop)
 * @property bool $IsDeleted
 * @property DateTime $CreatedOn
 * @property DateTime $EditedOn
 * @property int $CreatedBy
 * @property int $EditedBy
 */
class DM_TEMPLATE_EXPORT extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_template_excel";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "key",
        "name",
        "path_file_template",
        "path_file_template_doc",
        "content_edit",
        "IsDeleted",
        "CreatedOn",
        "EditedOn",
        "CreatedBy",
        "EditedBy",
    ];

    protected $attributes = [
        "_id" => 0,
        "key" => "",
        "name" => "",
        "path_file_template" => "",
        "path_file_template_doc" => "",
        "content_edit" => [],
        "IsDeleted" => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
