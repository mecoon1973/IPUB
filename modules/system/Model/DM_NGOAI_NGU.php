<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * Bảng ngôn ngữ
 * @property int $_id
 * @property string $MaNgoaiNgu
 * @property string $TenNgoaiNgu
 * @property string $ThuTu
 * @property bool $IsDeleted
 * @property bool $IsUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 */
class DM_NGOAI_NGU extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_ngoai_ngu";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaNgoaiNgu',
        'TenNgoaiNgu',
        'ThuTu',
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
        'MaNgoaiNgu' => '',
        'TenNgoaiNgu' => '',
        'ThuTu' => 0,
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
