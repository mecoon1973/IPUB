<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property int $ID_Donvi_LC
 * @property int $ID_LOAI_XBP_LC
 * @property int $SoLuong
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property bool $IsDeleted
 * @property bool $InUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 */
class CT_DONVILC_LOAIXBPLC extends Model {
    protected $connection = "olm";

    protected $table = "ipub_ct_donvi_loai_xbp_lc";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'ID_Donvi_LC',
        'ID_LOAI_XBP_LC',
        'SoLuong',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'IsDeleted',
        'InUsed',
        'DaGui',
        'KhoaGuiNhan',
    ];

    protected $attributes = [
        '_id' => 0,
        'ID_Donvi_LC' => 0,
        'ID_LOAI_XBP_LC' => 0,
        'SoLuong' => 0,
        'CreatedBy' => 0,
        // 'CreatedOn' => null,
        'EditedBy' => 0,
        // 'EditedOn' => null,
        'IsDeleted' => false,
        'InUsed' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
