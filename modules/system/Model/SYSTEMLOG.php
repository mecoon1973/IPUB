<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;
use Modules\User\Model\User;
use MongoDB\Laravel\Relations\BelongsTo;

/**
 * Hệ thống log
 * @property int $_id
 * @property int $UserID
 * @property string $Desc
 * @property string $IPAddress
 * @property DateTime $ActionTime
 * @property bool $InUse
 *
 * @property User $user
 */
class SYSTEMLOG extends Model {
    protected $connection = "olm";

    protected $table = "ipub_sys_log";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = false;

    protected $fillable = [
        "_id",
        "UserID",
        "Desc",
        "IPAddress",
        "ActionTime",
        "InUse",
    ];

    protected $attributes = [
        "_id" => 0,
        "UserID" => 0,
        "Desc" => "",
        "IPAddress" => "",
        "ActionTime" => null,
        "InUse" => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserID', '_id')->select(['_id', 'UserName', 'HoTen']);
    }
}
