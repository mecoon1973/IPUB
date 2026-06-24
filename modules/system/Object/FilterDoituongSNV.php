<?php
namespace Modules\System\Object;
use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * @property ?bool $IsDeleted
 * @property ?bool $InUsed
 * @property ?string $TenDonVi
 *
 */
class FilterDoituongSNV extends BaseObject {
    public function __construct($input = []) {
        parent::__construct($input);
    }
    public function buildConditions() {
        $conditions = [];
        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }
        if($this->InUsed !== null) {
            $conditions["InUsed"] = (bool)$this->InUsed;
        }
        if($this->TenDonVi !== null && $this->TenDonVi !== "") {
            $conditions["TenDonVi"] = ['$regex' =>  new Regex(preg_quote($this->TenDonVi, "/"), "ui")];
        }
        return $conditions;
    }
}
