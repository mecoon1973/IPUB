<?php
namespace Modules\System\Model;

use Core\Model\Model;

/**
 * Don vi này là bảng cũ DM_DONVI
 * @property int $_id
 * @property string $MaDonVi
 * @property string $TenDonVi
 * @property string $DiaChi
 * @property string $Website
 * @property string $Email
 * @property string $SoDienThoai
 * @property string $SoFax
 * @property boolean $NhaIn
 * @property boolean $DauThau
 * @property boolean $BienTap
 * @property boolean $LienKet
 * @property boolean $NoiBo
 * @property string $MST
 * @property string $SoTaiKhoan
 * @property string $TaiNganHang
 * @property string $MaSoPhu
 * @property int $ParentID
 * @property int $ThuTu
 * @property string $ID_Childs
 * @property boolean $Active
 * @property datetime $EditedOn
 * @property int $EditedBy
 * @property int $CreateBy
 * @property datetime $CreateOn
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property boolean $DaGui
 * @property int $DiaChi
 * @property string $KhoaGuiNhan
 * @property string $TinhThanh
 * @property string $MaTinh
 * @property string $LicenseKey
 * @property int $NgayTTPQLXB
 * @property boolean $IsCreateQDXB
 * @property string $KiHieuMoi
 * @property string $KiHieuTaiBan
 * @property string $KiHieuPhu
 *
 */
class DM_DONVI extends Model
{

	protected $connection = 'olm';

	protected $table = 'ipub_dm_donvi';

    protected $primaryKey = '_id';
    protected $keyType = "int";
	public $timestamps = true;

	public $timestamps2 = true;

	protected $fillable = [
		'_id',
        'MaDonVi',
        'TenDonVi',
        'DiaChi',
        'Website',
        'Email',
        'SoDienThoai',
        'SoFax',
        'NhaIn',
        'DauThau',
        'BienTap',
        'LienKet',
        'NoiBo',
        'MST',
        'SoTaiKhoan',
        'TaiNganHang',
        'MaSoPhu',
        'ParentID',
        'ThuTu',
        'ID_Childs',
        'Active',
        'EditedOn',
        'EditedBy',
        'CreateBy',
        'CreateOn',
        'InUsed',
        'IsDeleted',
        'DaGui',
        'KhoaGuiNhan',
        'TinhThanh',
        'MaTinh',
        'LicenseKey',
        'NgayTTPQLXB',
        'IsCreateQDXB',
        'KiHieuMoi',
        'KiHieuTaiBan',
        'KiHieuPhu',

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
		'MaDonVi' => '',
        'TenDonVi' => '',
        'DiaChi' => '',
        'Website' => '',
        'Email' => '',
        'SoDienThoai' => '',
        'SoFax' => '',
        'NhaIn' => false,
        'DauThau' => false,
        'BienTap' => false,
        'LienKet' => false,
        'NoiBo' => false,
        'MST' => '',
        'SoTaiKhoan' => '',
        'TaiNganHang' => '',
        'MaSoPhu' => '',
        'ParentID' => 0,
        'ThuTu' => 0,
        'ID_Childs' => '',
        'Active' => false,
        'EditedOn' => null,
        'EditedBy' => 0,
        'CreateBy' => 0,
        'CreateOn' => null,
        'InUsed' => false,
        'IsDeleted' => false,
        'DaGui' => false,
        'KhoaGuiNhan' => '',
        'TinhThanh' => '',
        'MaTinh' => '',
        'LicenseKey' => '',
        'NgayTTPQLXB' => 0,
        'IsCreateQDXB' => false,
        'KiHieuMoi' => '',
        'KiHieuTaiBan' => '',
        'KiHieuPhu' => '',
	];
}

