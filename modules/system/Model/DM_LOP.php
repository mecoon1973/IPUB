<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $MaLop
 * @property string $TenLop
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property boolean $DaGui
 * @property string $KhoaGuiNhan
 * @property string $KiHieu
 */
class DM_LOP extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_lop";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaLop',
        'TenLop',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'InUsed',
        'IsDeleted',
        'DaGui',
        'KhoaGuiNhan',
        'KiHieu',
    ];

    protected $attributes = [
        '_id' => 0,
        'MaLop' => '',
        'TenLop' => '',
        'CreatedBy' => 0,
        'EditedBy' => 0,
        'InUsed' => true,
        'IsDeleted' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'CreatedOn' => null,
        'EditedOn' => null,
        'KiHieu' => "",
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
