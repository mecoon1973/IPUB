<?php

namespace Modules\LegalDeposit\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property int $Id_PhieuLuuChieu
 * @property int $Id_Sach
 * @property int $Id_ToKhai
 * @property bool $InUsed
 * @property bool $InDeleted
 * @property DateTime $NgaySua
 * @property DateTime $NgayTao
 * @property int $NguoiTao
 * @property int $NguoiSua
 *
 */
class CT_ToKhaiLuuChuyen extends Model {
    protected $connection = "olm";

    protected $table = "ipub_chi_tiet_to_khai_luu_chieu";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "Id_PhieuLuuChieu",
        "Id_Sach",
        "Id_ToKhai",
        "InUsed",
        "InDeleted",
        "NgaySua",
        "NgayTao",
        "NguoiTao",
        "NguoiSua",
    ];

    protected $attributes = [
        "_id" => 0,
        "Id_PhieuLuuChieu" => 0,
        "Id_Sach" => 0,
        "Id_ToKhai" => 0,
        "InUsed" => false,
        "InDeleted" => false,
        "NgaySua" => null,
        "NgayTao" => null,
        "NguoiTao" => 0,
        "NguoiSua" => 0,
    ];

    protected $casts = [

    ];

    protected $customCasts = [];
}
