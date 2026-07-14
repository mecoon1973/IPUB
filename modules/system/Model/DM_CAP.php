<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;
/**
 * @property int $_id
 * @property string $TenCap
 * @property string $KiHieu
 * @property string $MaCap
 * @property string $MoTa
 * @property bool $DaGui
 * @property bool $InUsed
 * @property bool $IsDeleted
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property int $EditedBy
 * @property DateTime $CreatedOn
 * @property DateTime $EditedOn
 */
class DM_CAP extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_cap";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "TenCap",
        "KiHieu",
        "MaCap",
        "MoTa",
        "DaGui",
        "InUsed",
        "IsDeleted",
        "KhoaGuiNhan",
        "CreatedBy",
        "EditedBy",
        "CreatedOn",
        "EditedOn",
    ];

    protected $attributes = [
        "TenCap" => "",
        "KiHieu" => "",
        "MaCap" => "",
        "MoTa" => "",
        "DaGui" => false,
        "InUsed" => false,
        "IsDeleted" => false,
        "KhoaGuiNhan" => "",
        "CreatedBy" => 0,
        "EditedBy" => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
