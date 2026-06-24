<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * Bảng loại xuất bản
 * @property int $_id
 * @property string $TenLoai
 * @property string $MaLoai
 * @property string $MoTa
 * @property string $KiHieu
 * @property int $Type
 * @property bool $IsDeleted
 * @property bool $IsUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property datetime $EditedOn
 */
class DM_LOAI_XBP extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_loai_xbp";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'TenLoai',
        'MaLoai',
        'MoTa',
        'KiHieu',
        'Type',
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
        'TenLoai' => '',
        'MaLoai' => '',
        'MoTa' => '',
        'KiHieu' => '',
        'Type' => 0,
        'IsDeleted' => false,
        'IsUsed' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'CreatedBy' => 0,
        'EditedBy' => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
