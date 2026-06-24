<?php

namespace Modules\System\Model;

use Core\Model\Model;
/**
 * Bảng phân hệ
 * @property int $id
 * @property int $CreatedBy
 * @property datetime $CreatedOn
 * @property int $EditedBy
 * @property datetime $EditedOn
 * @property boolean $InUsed
 * @property boolean $Deleted
 * @property boolean $TenPhanHe
 * @property string $Type
 * @property string $Order
 * @property string $Code
 */
class DM_PHANHE extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_phanhe";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        'id',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'InUsed',
        'Deleted',
        'TenPhanHe',
        'Type',
        'Order',
        'Code',
    ];

    protected $attributes = [
        'id' => 0,
        'CreatedBy' => 0,
        'CreatedOn' => null,
        'EditedBy' => 0,
        'EditedOn' => null,
        'InUsed' => false,
        'Deleted' => false,
        'TenPhanHe' => '',
        'Type' => '',
        'Order' => 0,
        'Code' => '',
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
