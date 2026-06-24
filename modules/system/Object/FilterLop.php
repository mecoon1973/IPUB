<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;


/**
 * @property ?bool $IsDeleted
 */
class FilterLop extends BaseObject {
    public ?bool $IsDeleted = null;

    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }

        return $conditions;
    }
}
