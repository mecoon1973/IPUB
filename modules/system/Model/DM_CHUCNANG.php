<?php

namespace Modules\System\Model;

use Core\Model\Model;
use DateTime;

/**
 * Bảng chức năng
 * @property int $_id
 * @property string $Code
 * @property string $Title
 * @property int $ParentID thằng cha
 * @property string $NodeID
 * @property string $Href đường dẫn
 * @property boolean $Leaf có phải là node lá không
 * @property string $ChildFunctionCode các function con
 * @property string $NameID
 * @property boolean $Visible
 * @property boolean $Root
 * @property int $Position
 * @property string $Description
 * @property boolean $Deleted
 * @property boolean $NotChange
 * @property string $StatusCode
 * @property int $PhanHeID
 * @property int $Order thứ tự ưu tiên
 * @property int $ThuTu thứ tự ưu tiên == Order thêm để dùng chung lớp BaseTreeEntity
 * @property string $CreatedBy
 * @property DateTime $CreatedOn
 * @property string $EditedBy
 * @property Datetime $EditedOn
 * @property string $FunctionCode
 * @property boolean $OnMenu có hiển thị ở header không
 * @property string $Icon
 * @property string $Crumb
 * @property string $Target
 * @property boolean $isLinkFull
 *
 * @property DM_CHUCNANG $Parent
 */
class DM_CHUCNANG extends Model {
    protected $connection = "olm";

    protected $table = "ipub_function";
	public $timestamps = false;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        '_id',
        'Code',
        'Title',
        'ParentID',
        'NodeID',
        'Href',
        'Leaf',
        'ChildFunctionCode',
        'NameID',
        'Visible',
        'Root',
        'Position',
        'Description',
        'Deleted',
        'NotChange',
        'StatusCode',
        'PhanHeID',
        'Order',
        'ThuTu',
        'CreatedBy',
        'CreatedOn',
        'EditedBy',
        'EditedOn',
        'FunctionCode',
        'OnMenu',
        'Icon',
        'Crumb',
        'Target',
        'isLinkFull',
    ];

    protected $attributes = [
        '_id' => 0,
        'Code' => '',
        'Title' => '',
        'ParentID' => 0,
        'NodeID' => '',
        'Href' => '',
        'Leaf' => false,
        'ChildFunctionCode' => '',
        'NameID' => '',
        'Visible' => false,
        'Root' => false,
        'Position' => 0,
        'Description' => '',
        'Deleted' => false,
        'NotChange' => false,
        'StatusCode' => '',
        'PhanHeID' => 0,
        'Order' => 0,
        'ThuTu' => 0,
        'CreatedBy' => 0,
        'CreatedOn' => null,
        'EditedBy' => 0,
        'EditedOn' => null,
        'FunctionCode' => '',
        'OnMenu' => false,
        'Icon' => '',
        'Crumb' => '',
        'Target' => '',
        'isLinkFull' => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];

    public function Parent() {
        return $this->belongsTo(DM_CHUCNANG::class, 'ParentID', '_id');
    }
}
