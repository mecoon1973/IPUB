<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;

/**
 * Quyết định in
 * @property int $_id
 * @property string $CanCu
 * @property string $DiaDanh
 * @property string $HTXB
 * @property int $ID_DVQD_VMS
 * @property int $ID_DV_QD
 * @property int $ID_MangSachQDIN
 * @property int $ID_NguoiKi
 * @property string $ID_VMS
 * @property string $MaDonviQD
 * @property string $NamKeHoach
 * @property string $NoiNhan
 * @property string $SoQD
 * @property string $TenDonViQD
 * @property string $TenDonVi_VMS
 * @property string $TenNguoiKi
 * @property string $TieuDe
 * @property string $UserName_VMS
 * @property int $SoQDTuTang
 * @property DateTime $NgayQD
 * @property bool $DaGui
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property string $KhoaGuiNhan
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 */
class QDIn extends Model {
    protected $connection = "olm";

    protected $table = "ipub_qd_in";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "CanCu",
        "DiaDanh",
        "HTXB",
        "ID_DVQD_VMS",
        "ID_DV_QD",
        "ID_MangSachQDIN",
        "ID_NguoiKi",
        "ID_VMS",
        "MaDonviQD",
        "NamKeHoach",
        "NoiNhan",
        "SoQD",
        "TenDonViQD",
        "TenDonVi_VMS",
        "TenNguoiKi",
        "TieuDe",
        "UserName_VMS",
        "SoQDTuTang",
        "NgayQD",
        "DaGui",
        "InUsed",
        "IsDeleted",
        "KhoaGuiNhan",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
    ];

    protected $attributes = [
        "_id" => 0,
        "CanCu" => "",
        "DiaDanh" => "",
        "HTXB" => 0,
        "ID_DVQD_VMS" => 0,
        "ID_DV_QD" => 0,
        "ID_MangSachQDIN" => 0,
        "ID_NguoiKi" => 0,
        "ID_VMS" => "",
        "MaDonviQD" => "",
        "NamKeHoach" => "",
        "NoiNhan" => "",
        "SoQD" => "",
        "TenDonViQD" => "",
        "TenDonVi_VMS" => "",
        "TenNguoiKi" => "",
        "TieuDe" => "",
        "UserName_VMS" => "",
        "SoQDTuTang" => 0,
        "NgayQD" => null,
        "DaGui" => false,
        "InUsed" => false,
        "IsDeleted" => false,
        "KhoaGuiNhan" => "",
        "CreatedBy" => 0,
        "EditedBy" => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
