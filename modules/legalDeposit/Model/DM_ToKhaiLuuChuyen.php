<?php

namespace Modules\LegalDeposit\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $TieuDe
 * @property DateTime $NgayTao
 * @property int $NguoiTao
 * @property string $NoiNhan
 * @property string $NguoiKhai
 * @property bool $isCucXB
 * @property bool $inUsed
 * @property bool $isDeleted
 * @property DateTime $NgayXacNhan
 * @property int $NguoiSua
 * @property string $NguoiNhan
 * @property int $SoThuTuTu
 * @property int $SoThuTuDen
 * @property DateTime $NgayBatDau
 * @property DateTime $NgayKetThuc
 * @property string $MaDonVi
 * @property string $HTXB
 * @property bool $LaNoiBan
 * @property int $SoTK
 * @property string $SoTKText
 */
class DM_ToKhaiLuuChuyen extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_tokhailuuchuyen";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "TieuDe",
        "NgayTao",
        "NguoiTao",
        "NoiNhan",
        "NguoiKhai",
        "isCucXB",
        "inUsed",
        "isDeleted",
        "NgayXacNhan",
        "NguoiSua",
        "NguoiNhan",
        "SoThuTuTu",
        "SoThuTuDen",
        "NgayBatDau",
        "NgayKetThuc",
        "MaDonVi",
        "HTXB",
        "LaNoiBan",
        "SoTK",
        "SoTKText",
    ];

    protected $attributes = [
        "_id" => 0,
        "TieuDe" => "",
        // "NgayTao" => null,
        "NguoiTao" => 0,
        "NoiNhan" => "",
        "NguoiKhai" => "",
        "isCucXB" => false,
        "inUsed" => false,
        "isDeleted" => false,
        // "NgayXacNhan" => null,
        "NguoiSua" => 0,
        "NguoiNhan" => "",
        "SoThuTuTu" => 0,
        "SoThuTuDen" => 0,
        // "NgayBatDau" => null,
        // "NgayKetThuc" => null,
        "MaDonVi" => "",
        "HTXB" => "",
        "LaNoiBan" => false,
        "SoTK" => 0,
        "SoTKText" => "",
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
