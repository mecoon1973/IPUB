<?php
namespace Modules\System\Object;
use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * @property ?bool $IsDeleted
 * @property ?string $Ten
 */
class FilterDonviLC extends BaseObject {
    public ?bool $IsDeleted = null;
    public ?string $Ten = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions() {
        $conditions = [];
        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }
        if($this->Ten !== null && $this->Ten !== "") {
            $conditions["Ten"] = ['$regex' =>  new Regex(preg_quote($this->Ten, "/"), "ui")];
        }
        return $conditions;
    }

    public function buildSort() {
        $sort = [
            "ThuTu" => 1,
        ];
        return $sort;
    }
}
