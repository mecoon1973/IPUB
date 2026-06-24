<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * bảng đối tượng nhận sách nghiệp vụ
 * @property int $_id
 * @property string $TenDonVi
 * @property string $ThuTu
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property boolean $IsDeleted
 * @property boolean $InUsed
 * @property string $KhoaGuiNhan
 *
 * @property array $listLoaiSNV
 */
class DM_DOITUONG_SNV extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_donvi_snv";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "TenDonVi",
        "ThuTu",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
        "IsDeleted",
        "InUsed",
        "KhoaGuiNhan",
        "listLoaiSNV",
    ];

    protected $attributes = [
        "_id" => 0,
        "TenDonVi" => "",
        "ThuTu" => 0,
        "CreatedBy" => 0,
        "EditedBy" => 0,
        "IsDeleted" => false,
        "InUsed" => true,
        "KhoaGuiNhan" => "",
        "listLoaiSNV" => [],
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
