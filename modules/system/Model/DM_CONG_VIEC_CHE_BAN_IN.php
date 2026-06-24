<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $TenCongViec
 * @property string $MaCongViec
 * @property bool $IsDeleted
 * @property bool $InUsed
 * @property bool $DaGui
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 */
class DM_CONG_VIEC_CHE_BAN_IN extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_cong_viec_che_ban_in";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'TenCongViec',
        'MaCongViec',
        'IsDeleted',
        'InUsed',
        'DaGui',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'TenCongViec' => '',
        'MaCongViec' => '',
        'IsDeleted' => false,
        'InUsed' => false,
        'DaGui' => false,
        'CreatedBy' => 0,
        'EditedBy' => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
