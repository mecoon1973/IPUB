<?php
namespace Modules\User\Model;


use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Core\Model\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\System\Model\DM_DONVI;
use Modules\System\Model\DM_CHUYENMON;
use Modules\System\Model\DM_CHUCVU;

/**
 * User này là bảng cũ DM_CANBO
 * @property int $_id
 * @property string $MaCanBo Ma cán bộ
 * @property string $HoTen Ho tên
 * @property \Carbon\Carbon|null $NgaySinh Lưu Mongo dạng BSON Date (UTCDateTime)
 * @property int $ID_ChucVu
 * @property string $ChucVuText
 * @property int $ID_DonVi
 * @property int $ID_ChuyenMon
 * @property string $SoDienThoai
 * @property string $Email
 * @property string $DiaChi
 * @property string $UserName
 * @property string $PassWord
 * @property boolean $IsActive
 * @property boolean $IsEditor là biên tập viên
 * @property string $UserThemes
 * @property \Carbon\Carbon|null $NgayHetHan Lưu Mongo dạng BSON Date
 * @property int $SoLuongBanGhi
 * @property int $ID_Scale
 * @property int $NguoiKi
 * @property int $CreateBy
 * @property \Carbon\Carbon|null $CreateOn Lưu Mongo dạng BSON Date
 * @property int $EditedBy
 * @property \Carbon\Carbon|null $EditedOn Lưu Mongo dạng BSON Date
 * @property int $InUsed
 * @property int $IsDeleted
 * @property int $DaGui
 * @property string $KhoaGuiNhan
 * @property string $MaSoChungChi
 * @property \Carbon\Carbon|null $NgayCap Lưu Mongo dạng BSON Date
 * @property string $NoiCap
 * @property string $ChucDanhBienTap
 * @property boolean $isSpecial
 * @property boolean $KyQDXB
 * @property boolean $UQKyQDXB
 * @property boolean $NguoiSoanThao
 * @property boolean $KyNhayQDXB
 * @property string $ID_VSSIGN
 * @property string $SignatureUrl_VSSIGN
 * @property boolean $isActive_VSSIGN
 * @property array $nhom_ids  // array các id nhóm cán bộ
 * @property array $quyen_ids  // array các id quyền cán bộ
 *
 *
 *
 * @property DM_DONVI $donvi  // đơn vị cán bộ
 * @property DM_CHUYENMON $chuyenmon  // chuyên môn cán bộ
 * @property DM_CHUCVU $chucvu  // chức vụ cán bộ
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
	use Authenticatable, Authorizable; //SoftDeletes;

	protected $connection = 'olm';

	protected $table = 'ipub_dm_canbo';

    protected $primaryKey = '_id';

    public $incrementing = true;

    /**
     * Không dùng timestamps Laravel; CreateOn/EditedOn gán thủ công.
     * Cast datetime: driver MongoDB ghi BSON Date (UTCDateTime), không phải string.
     */
	public $timestamps = false;

	public $timestamps2 = true;

    protected $casts = [
        'NgaySinh' => 'datetime',
        'NgayHetHan' => 'datetime',
        'NgayCap' => 'datetime',
        'CreateOn' => 'datetime',
        'EditedOn' => 'datetime',
    ];

	protected $fillable = [
		'_id',
		'MaCanBo',
		'HoTen',
		'NgaySinh',
		'ID_ChucVu',
		'ChucVuText',
		'ID_DonVi',
		'ID_ChuyenMon',
		'SoDienThoai',
		'Email',
		'DiaChi',
		'UserName',
		'PassWord',
		'IsActive',
		'IsEditor',
		'UserThemes',
		'NgayHetHan',
		'SoLuongBanGhi',
		'ID_Scale',
		'NguoiKi',
		'CreateBy',
		'CreateOn',
		'EditedBy',
		'EditedOn',
		'InUsed',
		'IsDeleted',
		'DaGui',
		'KhoaGuiNhan',
		'MaSoChungChi',
		'NgayCap',
		'NoiCap',
		'ChucDanhBienTap',
		'isSpecial',
		'KyQDXB',
		'UQKyQDXB',
		'NguoiSoanThao',
		'KyNhayQDXB',
		'ID_VSSIGN',
		'SignatureUrl_VSSIGN',
		'isActive_VSSIGN',
		'nhom_ids',
		'quyen_ids',

	];

	protected $hidden = [
		'password',
	];

	protected $appends = [

	];

	protected $attributes = [
		"_id" => 0,
		'MaCanBo' => '',
		'HoTen' => '',
		'NgaySinh' => null,
		'ID_ChucVu' => 0,
		'ChucVuText' => '',
		'ID_DonVi' => 0,
		'ID_ChuyenMon' => 0,
		'SoDienThoai' => '',
		'Email' => '',
		'DiaChi' => '',
		'UserName' => '',
		'PassWord' => '',
		'IsActive' => false,
		'IsEditor' => false,
		'UserThemes' => '',
		'NgayHetHan' => null,
		'SoLuongBanGhi' => 0,
		'ID_Scale' => 0,
		'NguoiKi' => 0,
		'CreateBy' => 0,
		'CreateOn' => null,
		'EditedBy' => 0,
		'EditedOn' => null,
		'InUsed' => 0,
		'IsDeleted' => 0,
		'DaGui' => '',
		'KhoaGuiNhan' => '',
		'MaSoChungChi' => '',
		'NgayCap' => null,
		'NoiCap' => '',
		'ChucDanhBienTap' => '',
		'isSpecial' => false,
		'KyQDXB' => false,
		'UQKyQDXB' => false,
		'NguoiSoanThao' => false,
		'KyNhayQDXB' => false,
		'ID_VSSIGN' => '',
		'SignatureUrl_VSSIGN' => '',
		'isActive_VSSIGN' => false,
		'nhom_ids' => [],
		'quyen_ids' => [],
	];

    public function donvi(): BelongsTo
    {
        return $this->belongsTo(DM_DONVI::class, 'ID_DonVi', '_id');
    }

    public function chuyenmon(): BelongsTo
    {
        return $this->belongsTo(DM_CHUYENMON::class, 'ID_ChuyenMon', '_id');
    }
    public function chucvu(): BelongsTo
    {
        return $this->belongsTo(DM_CHUCVU::class, 'ID_ChucVu', '_id');
    }
}

