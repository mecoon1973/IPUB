<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * Bảng nhóm quyền
 * @property int $id
 * @property string $MaNhomNSD
 * @property string $TenNhomNSD
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property boolean $DaGui
 * @property string $KhoaGuiNhan
 *
 *
 * @property array $listIdQuyen
 * */
class DM_NHOM extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_nhom";

	protected $primaryKey = '_id';
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

	public $timestamps = false;


    protected $fillable = [
        'id',
        'MaNhomNSD',
        'TenNhomNSD',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'InUsed',
        'IsDeleted',
        'DaGui',
        'KhoaGuiNhan',
        'listIdQuyen',
    ];

    protected $attributes = [
        'id' => 0,
        'MaNhomNSD' => '',
        'TenNhomNSD' => '',
        'CreatedBy' => 0,
        'EditedBy' => 0,
        'InUsed' => true,
        'IsDeleted' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'listIdQuyen' => [],
    ];

    protected $casts = [
        'CreatedOn' => 'datetime',
        'EditedOn' => 'datetime',
    ];

    protected $customCasts = [];
}
