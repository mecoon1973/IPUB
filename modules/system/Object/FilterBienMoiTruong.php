<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * @property ?string $ConfigSearch
 * @property ?int $id_Dv
 */
class FilterBienMoiTruong extends BaseObject {

    public ?string $ConfigSearch = null;
    public ?int $id_Dv = null;

    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->ConfigSearch !== null && $this->ConfigSearch !== "") {
            $regex = ['$regex' => new Regex(preg_quote($this->ConfigSearch, "/"), "ui")];
            $conditions['$or'] = [
                ["ConfigName" => $regex],
                ["ConfigValue" => $regex],
                ["ConfigNotes" => $regex],
            ];
        }

        if($this->id_Dv !== null && $this->id_Dv > 0) {
            $conditions["id_Dv"] = (int)$this->id_Dv;
        }
        return $conditions;
    }
}
