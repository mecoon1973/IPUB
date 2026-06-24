<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * @property ?string $textSearch
 * @property ?bool $IsDeleted
 */
class FilterLoaiXBP extends BaseObject {
    public ?string $textSearch = null;
    public ?bool $IsDeleted = null;

    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->textSearch !== null) {
            $conditions['$or'] = [
                ["TenLoai" => ['$regex' => new Regex(preg_quote($this->textSearch, "/"), "ui")]],
                ["MaLoai" => ['$regex' => new Regex(preg_quote($this->textSearch, "/"), "ui")]],
                ["KiHieu" => ['$regex' => new Regex(preg_quote($this->textSearch, "/"), "ui")]],
            ];
        }

        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }

        return $conditions;
    }
}
