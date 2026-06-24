<?php
namespace Modules\System\Object;
use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * @property ?bool $IsDeleted
 * @property ?bool $InUsed
 * @property ?string $TenLoai
 */
class FilterLoaiXbpLc extends BaseObject {

    public ?bool $IsDeleted = null;
    public ?bool $InUsed = null;
    public ?string $TenLoai = null;

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
        if($this->TenLoai !== null && $this->TenLoai !== "") {
            $conditions["TenLoai"] = ['$regex' => new Regex(preg_quote($this->TenLoai, "/"), "ui")];
        }
        return $conditions;
    }
}
