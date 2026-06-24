<?php
namespace Modules\System\Model;

use Core\Model\Model;

/**
 * Quyền này là bảng cũ DM_QUYEN
 * @property int $_id
 * @property int $CreatedBy
 * @property int $CreatedOn
 * @property boolean $DaGui
 * @property int $EditedBy
 * @property int $EditedOn
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property string $KhoaGuiNhan
 * @property int $ParentID
 * @property string $MaQuyen
 * @property string $TenQuyen
 * @property int $ThuTu
 *
 *
 * @property array $listIdFunctions
 *
 */
class DM_QUYEN extends Model
{

	protected $connection = 'olm';

	protected $table = 'ipub_dm_quyen';

    protected $primaryKey = '_id';
    protected $keyType = "int";

    public $incrementing = true;

	public $timestamps = false;
	public $timestamps2 = true;


	protected $fillable = [
		'_id',
        'MaQuyen',
        'TenQuyen',
        'ThuTu',
        'IsDeleted',
        'DaGui',
        'KhoaGuiNhan',
        'InUsed',
        'ParentID',
        'listIdFunctions',
        'EditedOn',
        'EditedBy',
        'CreateBy',
        'CreateOn',
	];

	protected $hidden = [

	];

	protected $appends = [

	];

    protected $casts = [
        'CreateOn' => 'datetime',
        'EditedOn' => 'datetime',
    ];

	protected $attributes = [
		"_id" => 0,
		'MaQuyen' => '',
        'TenQuyen' => '',
        'ThuTu' => 0,
        'IsDeleted' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'InUsed' => false,
        'EditedBy' => 0,
        'ParentID' => 0,
        'CreateBy' => 0,
        // 'EditedOn' => null,
        // 'CreateOn' => null,
        'listIdFunctions' => [],
	];
}

