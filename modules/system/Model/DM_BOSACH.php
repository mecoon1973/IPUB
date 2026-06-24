<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $MaBo
 * @property string $TenBo
 * @property string $MoTa
 * @property string $KiHieu
 * @property bool $IsDeleted
 * @property bool $IsUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property Datetime $EditedOn
 */
class DM_BOSACH extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_bo_sach";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaBoSach',
        'TenBoSach',
        'MoTa',
        'KiHieu',
        'IsDeleted',
        'IsUsed',
        'DaGui',
        'KhoaGuiNhan',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'MaBoSach' => '',
        'TenBoSach' => '',
        'MoTa' => '',
        'KiHieu' => '',
        'IsDeleted' => false,
        'IsUsed' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'CreatedBy' => 0,
        'CreatedOn' => null,
        'EditedBy' => 0,
        'EditedOn' => null,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
