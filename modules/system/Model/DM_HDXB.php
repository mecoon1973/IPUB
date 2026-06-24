<?php
namespace Modules\System\Model;

use Core\Model\Model;

/**
 * Don vi này là bảng cũ DM_DONVI
 * @property int $_id
 * @property int $CreatedBy
 * @property int $CreatedOn
 * @property boolean $DaGui
 * @property string $Description
 * @property int $EditedBy
 * @property int $EditedOn
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property string $KhoaGuiNhan
 * @property string $MaHDXB
 * @property string $TenHDXB
 * @property int $Thutu
 *
 */
class DM_HDXB extends Model
{

	protected $connection = 'olm';

	protected $table = 'ipub_dm_hdxb';
    protected $keyType = "int";

    protected $primaryKey = '_id';

    public $incrementing = true;

	public $timestamps = false;
	public $timestamps2 = true;


	protected $fillable = [
		'_id',
        'MaHDXB',
        'TenHDXB',
        'ThuTu',
        'IsDeleted',
        'DaGui',
        'KhoaGuiNhan',
        'Description',
        'InUsed',
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
		'MaHDXB' => '',
        'TenHDXB' => '',
        'ThuTu' => 0,
        'IsDeleted' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'Description' => '',
        'InUsed' => false,
        'EditedOn' => null,
        'EditedBy' => 0,
        'CreateBy' => 0,
        'CreateOn' => null,
	];
}

