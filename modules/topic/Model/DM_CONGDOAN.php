<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;

/**
 * Đây là bảng chưa các công đoạn từ lúc tạo đề tài đến lúc chuyển thành sách
 *  @property int $_id
 *  @property string $tencd
 *  @property string $macd
 *  @property bool $inused
 *  @property int $CreatedBy
 *  @property DateTime $CreatedOn
 *  @property int $EditedBy
 *  @property datetime $EditedOn
 */
class DM_CONGDOAN extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_congdoan";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'tencd',
        'macd',
        'inused',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
    ];

    protected $attributes = [
        '_id' => 0,
        'tencd' => '',
        'macd' => '',
        'inused' => false,
        'CreatedBy' => 0,
        'EditedBy' => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
