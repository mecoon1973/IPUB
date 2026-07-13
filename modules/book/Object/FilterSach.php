<?php
namespace Modules\Book\Object;
use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 *  FilterSach
 *  @property string $title
 *  @property string $MaSo
 *  @property int $ID_MangSach
 *  @property int $ID_DonVi
 *  @property string $NamXuatBan
 *  @property string $NamTaiBan
 *  @property int $HTXB
 *  @property array $NgayDK
 *  @property bool $IsDeleted
 *  @property bool $KetChuyenThanhSach
 *  @property int $id
 */
class FilterSach extends BaseObject {

    public string $title = "";
    public string $MaSo = "";
    public int $ID_MangSach = 0;
    public int $ID_DonVi = 0;
    public string $NamXuatBan = "";
    public string $NamTaiBan = "";
    public int $HTXB = -1;
    public array $NgayDK = [];
    public ?bool $IsDeleted = null;
    public bool $KetChuyenThanhSach = false;
    public int $id = 0;

    public function __construct($input = []) {
        parent::__construct($input);
    }
    public function buildConditions() {
        $conditions = [];
        if ($this->id !== null && $this->id !== 0) {
            $conditions['_id'] = (int) $this->id;
        }
        if ($this->title !== null && $this->title !== "") {
            $keyword = preg_quote($this->title, "/");
            $regex = new Regex($keyword, "ui");
            $conditions['$or'] = [
                ['MaSo' => $regex],
                ['TenSach' => $regex],
            ];
        }
        if ($this->MaSo !== null && $this->MaSo !== "") {
            $keyword = preg_quote($this->MaSo, "/");
            $regex = new Regex($keyword, "ui");
            $conditions['$or'] = [
                ["MaSo" => $regex],
            ];
        }
        if ($this->ID_MangSach !== null && $this->ID_MangSach !== 0) {
            $conditions["ID_MangSach"] = (int)$this->ID_MangSach;
        }
        if ($this->ID_DonVi !== null && $this->ID_DonVi !== 0) {
            $conditions["ID_DonVi"] = (int)$this->ID_DonVi;
        }
        if ($this->NamXuatBan !== null && $this->NamXuatBan !== "") {
            $conditions["NamXuatBan"] = $this->NamXuatBan;
        }
        if ($this->NamTaiBan !== null && $this->NamTaiBan !== "") {
            $conditions["NamTaiBan"] = $this->NamTaiBan;
        }
        if(isset($conditions["NamTaiBan"]) && isset($conditions["NamXuatBan"])){
            $conditions['$or'] = [
                ["NamTaiBan" => ['$gte' => $conditions["NamTaiBan"], '$lte' => $conditions["NamXuatBan"]]],
                ["NamXuatBan" => ['$gte' => $conditions["NamTaiBan"], '$lte' => $conditions["NamXuatBan"]]],
            ];
            unset($conditions["NamTaiBan"]);
            unset($conditions["NamXuatBan"]);
        }
        if ($this->HTXB !== null && in_array($this->HTXB, [0, 1])) {
            $conditions["HTXB"] = (bool) $this->HTXB;
        }
        if (is_array($this->NgayDK) && count($this->NgayDK) >= 2) {
            $conditions["NgayDK"] = ['$gte' => $this->NgayDK[0], '$lte' => $this->NgayDK[1]];
        }
        if ($this->IsDeleted !== null) {
            $conditions['IsDeleted'] = (bool) $this->IsDeleted;
        }
        if ($this->KetChuyenThanhSach) {
            $conditions['ID_DeTai'] = ['$gt' => 0];
        }
        return $conditions;
    }
}
