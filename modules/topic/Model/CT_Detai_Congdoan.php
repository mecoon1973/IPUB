<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;
use Modules\User\Model\User;

/**
 *  @property int $_id
 *  @property int $IDCongDoan
 *  @property int $IDDeTai
 *  @property int $IDSach
 *  @property string $MaCD
 *  @property string $GhiChu
 *  @property string $NewValue
 *  @property string $NoiDung
 *  @property string $OldValue
 *  @property DateTime $CreatedOn
 *  @property int $CreatedBy
 *  @property DateTime $EditedOn
 *  @property int $EditedBy
 *  @property User $user_create
 */
class CT_Detai_Congdoan extends Model {
    protected $connection = "olm";

    protected $table = "ipub_detai_congdoan";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "IDCongDoan",
        "IDDeTai",
        "IDSach",
        "MaCD",
        "GhiChu",
        "NewValue",
        "NoiDung",
        "OldValue",
        "CreatedOn",
        "CreatedBy",
        "EditedOn",
        "EditedBy",
    ];

    protected $attributes = [
        "_id" => 0,
        "IDCongDoan" => 0,
        "IDDeTai" => 0,
        "IDSach" => null,
        "MaCD" => "",
        "GhiChu" => "",
        "NewValue" => null,
        "NoiDung" => "",
        "OldValue" => null,
        "CreatedOn" => null,
        "CreatedBy" => 0,
        "EditedOn" => null,
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
