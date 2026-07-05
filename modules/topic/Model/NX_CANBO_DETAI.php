<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;

/**
 * Phân công cán bộ đọc duyệt / nhận xét đề tài NXBGDVN.
 *
 * @property int $_id
 * @property int $ID_CanBo Người được phân công đọc duyệt
 * @property int $ID_CanBoPhanCong Người thực hiện phân công
 * @property int $ID_DeTai
 * @property int $MaTrangThai
 * @property int $Duyet
 * @property string $NhanXet
 * @property bool $LaPhanCong
 * @property bool $DaGui
 * @property bool $InUsed
 * @property bool $IsDeleted
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime|null $CreatedOn
 * @property int|null $EditedBy
 * @property DateTime|null $EditedOn
 * @property DateTime|null $NgayNX
 */
class NX_CANBO_DETAI extends Model
{
    protected $connection = 'olm';

    protected $table = 'ipub_nx_canbo_detai';

    public $timestamps = false;

    protected $primaryKey = '_id';

    public $incrementing = true;

    public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'ID_CanBo',
        'ID_CanBoPhanCong',
        'ID_DeTai',
        'MaTrangThai',
        'Duyet',
        'NhanXet',
        'LaPhanCong',
        'DaGui',
        'InUsed',
        'IsDeleted',
        'KhoaGuiNhan',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'NgayNX',
    ];

    protected $attributes = [
        '_id' => 0,
        'ID_CanBo' => 0,
        'ID_CanBoPhanCong' => 0,
        'ID_DeTai' => 0,
        'MaTrangThai' => 0,
        'Duyet' => 0,
        'NhanXet' => '',
        'LaPhanCong' => true,
        'DaGui' => true,
        'InUsed' => true,
        'IsDeleted' => false,
        'KhoaGuiNhan' => '',
        'CreatedBy' => 0,
        'CreatedOn' => null,
        'EditedBy' => null,
        'EditedOn' => null,
        'NgayNX' => null,
    ];

    protected $casts = [
        'CreatedOn' => 'datetime',
        'EditedOn' => 'datetime',
        'NgayNX' => 'datetime',
    ];
}
