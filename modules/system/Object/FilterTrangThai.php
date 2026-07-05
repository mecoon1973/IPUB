<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;

/**
 * @property ?bool $DaGui
 * @property ?int $MaTrangThai
 */
class FilterTrangThai extends BaseObject {
    public ?bool $DaGui = null;
    public ?int $MaTrangThai = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions(): array {
        $conditions = [];

        if ($this->DaGui !== null) {
            $conditions["DaGui"] = (bool) $this->DaGui;
        }

        if ($this->MaTrangThai !== null) {
            $conditions["MaTrangThai"] = (int) $this->MaTrangThai;
        }

        return $conditions;
    }
}
