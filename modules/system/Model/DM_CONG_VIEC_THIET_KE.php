<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $TenCongViec
 * @property string $MaCongViec
 * @property string $DVT
 * @property bool $IsDeleted
 * @property bool $InUsed
 * @property bool $DaGui
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property datetime $EditedOn
 */
class DM_CONG_VIEC_THIET_KE extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_cong_viec_thiet_ke";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'TenCongViec',
        'MaCongViec',
        'DVT',
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
        'DVT' => '',
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
