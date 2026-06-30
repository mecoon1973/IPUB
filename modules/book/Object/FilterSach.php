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
 */
class FilterSach extends BaseObject {

    public string $title = "";
    public string $MaSo = "";
    public int $ID_MangSach = 0;
    public int $ID_DonVi = 0;
    public string $NamXuatBan = "";
    public string $NamTaiBan = "";
    public int $HTXB = 0;
    public array $NgayDK = [];

    public function __construct($input = []) {
        parent::__construct($input);
    }
    public function buildConditions() {
        $conditions = [];
        if ($this->title !== null && $this->title !== "") {
            $conditions['$or'] = [
                ["MaSo" => ['$regex' => new Regex(preg_quote($this->title, "/"), "ui")]],
                ["TenSach" => ['$regex' => new Regex(preg_quote($this->title, "/"), "ui")]],
            ];
        }
        if ($this->MaSo !== null && $this->MaSo !== "") {
            $conditions["MaSo"] =['$regex' => new Regex(preg_quote($this->MaSo, "/"))];
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
            $conditions["HTXB"] = (bool)$this->HTXB;
        }
        if (is_array($this->NgayDK) && count($this->NgayDK) >= 2) {
            $conditions["NgayDK"] = ['$gte' => $this->NgayDK[0], '$lte' => $this->NgayDK[1]];
        }
        return $conditions;
    }
}
