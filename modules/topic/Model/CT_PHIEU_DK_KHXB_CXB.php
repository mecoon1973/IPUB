<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;

/**
 * Chi tiết đề tài trong phiếu trình CXB
 *
 * @property int $_id
 * @property int $ID_PhieuDK
 * @property int $ID_DeTai
 * @property string $NamXuatBan
 * @property int $ThuTuTrongPhieu
 * @property int $TrangThai
 * @property string $LiDo
 * @property bool $DaGui
 * @property bool $InUsed
 * @property bool $IsDeleted
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime|null $CreatedOn
 * @property int $EditedBy
 * @property DateTime|null $EditedOn
 */
class CT_PHIEU_DK_KHXB_CXB extends Model
{
    protected $connection = 'olm';

    protected $table = 'ipub_ct_phieu_dk_khxb_cxb';

    public $timestamps = false;

    protected $primaryKey = '_id';

    public $incrementing = true;

    public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'ID_PhieuDK',
        'ID_DeTai',
        'NamXuatBan',
        'ThuTuTrongPhieu',
        'TrangThai',
        'LiDo',
        'DaGui',
        'InUsed',
        'IsDeleted',
        'KhoaGuiNhan',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'ID_PhieuDK' => 0,
        'ID_DeTai' => 0,
        'NamXuatBan' => '',
        'ThuTuTrongPhieu' => 0,
        'TrangThai' => 0,
        'LiDo' => '',
        'DaGui' => false,
        'InUsed' => true,
        'IsDeleted' => false,
        'KhoaGuiNhan' => '',
        'CreatedBy' => 0,
        'EditedBy' => 0,
    ];

    protected $casts = [
        'CreatedOn' => 'datetime',
        'EditedOn' => 'datetime',
        'DaGui' => 'boolean',
        'InUsed' => 'boolean',
        'IsDeleted' => 'boolean',
    ];

    protected $customCasts = [];
}
