<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * Môn học
 *
 * @property int $_id
 * @property string $MaMonHoc
 * @property string $TenMonHoc
 * @property string $MoTa
 * @property string $KiHieu
 * @property bool $IsDeleted
 * @property bool $IsUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property int $EditedBy
 * @property DateTime $CreatedOn
 * @property DateTime $EditedOn
 */
class DM_MONHOC extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_monhoc";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaMonHoc',
        'TenMonHoc',
        'MoTa',
        'KiHieu',
        'IsDeleted',
        'IsUsed',
        'DaGui',
        'KhoaGuiNhan',
        'CreatedBy',
        'EditedBy',
        'CreatedOn',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'MaMonHoc' => '',
        'TenMonHoc' => '',
        'MoTa' => '',
        'KiHieu' => '',
        'IsDeleted' => false,
        'IsUsed' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'CreatedBy' => 0,
        'EditedBy' => 0,
        'CreatedOn' => null,
        'EditedOn' => null,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
