<?php

namespace Modules\QualityAssessment\Model;

use Core\Model\Model;
use DateTime;
use Modules\User\Model\User;

/**
 * Bảng DS đề xuất ra soát
 * @property int $_id
 * @property string $Title
 * @property string $Type
 * @property bool $IsSach
 * @property bool $Deleted
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 *
 * @property ?User $user_create
 */
class DM_DSDocRaSoat extends Model {
    protected $connection = "olm";

    protected $table = "ipub_ds_docrasoat";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "Title",
        "Type",
        "IsSach",
        "Deleted",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
    ];

    protected $attributes = [
        "_id" => 0,
        "Title" => "",
        "Type" => "",
        "IsSach" => false,
        "Deleted" => false,
        "CreatedBy" => 0,
        "EditedBy" => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];

    public function user_create() {
        return $this->belongsTo(User::class, 'CreatedBy', '_id')->select(['_id', 'HoTen']);
    }
}
