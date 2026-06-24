<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * Danh mục đơn vị lưu chuyển
 * @property int $_id
 * @property string $Ten
 * @property int $ThuTu
 * @property string $KhoaGuiNhan
 * @property bool $IsDeleted
 * @property bool $InUsed
 * @property bool $DaGui
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 *
 *
 * @property array<CT_DONVILC_LOAIXBPLC> $LoaiXbpLc
 */
class DM_DONVILC extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_donvi_lc";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'Ten',
        'ThuTu',
        'KhoaGuiNhan',
        'IsDeleted',
        'InUsed',
        'DaGui',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'LoaiXbpLc',
    ];

    protected $attributes = [
        '_id' => 0,
        'Ten' => '',
        'ThuTu' => 0,
        'KhoaGuiNhan' => '',
        'IsDeleted' => false,
        'InUsed' => true,
        'DaGui' => false,
        'CreatedBy' => 0,
        'EditedBy' => 0,
        'LoaiXbpLc' => [],
        // 'CreatedOn' => null,
        // 'EditedOn' => null,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
