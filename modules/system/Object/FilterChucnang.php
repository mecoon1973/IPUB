<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;


/**
 * @property ?bool $Deleted
 */
class FilterChucnang extends BaseObject {
    public ?bool $Deleted = null;


    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->Deleted !== null) {
            $conditions["Deleted"] = (bool)$this->Deleted;
        }

        return $conditions;
    }
}
