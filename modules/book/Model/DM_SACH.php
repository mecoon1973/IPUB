<?php

namespace Modules\Book\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property bool $BanQuyen
 * @property DateTime $BanQuyenTuNgay
 * @property DateTime $BanQuyenDenNgay
 * @property string $BienTapBien
 *
 */
class DM_SACH extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_sach";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
    ];

    protected $attributes = [
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
