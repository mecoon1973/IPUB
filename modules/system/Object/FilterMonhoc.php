<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;


/**
 * @property ?bool $IsDeleted
 * @property ?bool $IsUsed
 */
class FilterMonhoc extends BaseObject {
    public ?bool $IsDeleted = null;
    public ?bool $IsUsed = null;
    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }

        if($this->IsUsed !== null) {
            $conditions["IsUsed"] = (bool)$this->IsUsed;
        }

        return $conditions;
    }

    public function buildSort() {
        $sort = ["ThuTu" => 1];

        return $sort;
    }
}
