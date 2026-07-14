<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;

/**
 * Phiếu đăng ký kế hoạch xuất bản — Cục xuất bản
 *
 * @property int $_id
 * @property string $MaSo
 * @property string $TieuDe
 * @property string $NoiDung
 * @property string $NoiNhan2
 * @property string $PhanDauMaSo
 * @property string $SoCvNXBGD
 * @property string $SoGiayPhep
 * @property DateTime|null $NgayDK
 * @property DateTime|null $NgayCapPhep
 * @property int|null $ID_NguoiKi
 * @property bool $KiThay
 * @property bool $DaGui
 * @property bool $InUsed
 * @property bool $IsDeleted
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime|null $CreatedOn
 * @property int $EditedBy
 * @property DateTime|null $EditedOn
 */
class PHIEU_DK_KHXB_CXB extends Model
{
    protected $connection = 'olm';

    protected $table = 'ipub_phieu_dk_khxb_cxb';

    public $timestamps = false;

    protected $primaryKey = '_id';

    public $incrementing = true;

    public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'MaSo',
        'TieuDe',
        'NoiDung',
        'NoiNhan2',
        'PhanDauMaSo',
        'SoCvNXBGD',
        'SoGiayPhep',
        'NgayDK',
        'NgayCapPhep',
        'ID_NguoiKi',
        'KiThay',
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
        'MaSo' => '',
        'TieuDe' => '',
        'NoiDung' => '',
        'NoiNhan2' => '',
        'PhanDauMaSo' => '',
        'SoCvNXBGD' => '',
        'SoGiayPhep' => '',
        'NgayDK' => null,
        'NgayCapPhep' => null,
        'ID_NguoiKi' => null,
        'KiThay' => false,
        'DaGui' => false,
        'InUsed' => true,
        'IsDeleted' => false,
        'KhoaGuiNhan' => '',
        'CreatedBy' => 0,
        'EditedBy' => 0,
    ];

    protected $casts = [
        'NgayDK' => 'datetime',
        'NgayCapPhep' => 'datetime',
        'CreatedOn' => 'datetime',
        'EditedOn' => 'datetime',
        'KiThay' => 'boolean',
        'DaGui' => 'boolean',
        'InUsed' => 'boolean',
        'IsDeleted' => 'boolean',
    ];

    protected $customCasts = [];
}
