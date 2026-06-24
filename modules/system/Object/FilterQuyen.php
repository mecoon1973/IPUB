<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;


/**
 * @property ?bool $IsDeleted
 * @property ?bool $InUsed
 * @property ?int $ParentID
 * @property ?string $MaQuyen
 */
class FilterQuyen extends BaseObject {
    public ?bool $IsDeleted = null;
    public ?int $ParentID = null;
    public ?string $MaQuyen = null;
    public ?bool $InUsed = null;
    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }
        if($this->ParentID !== null) {
            $conditions["ParentID"] = (int)$this->ParentID;
        }
        if($this->MaQuyen !== null) {
            $conditions["MaQuyen"] = (string)$this->MaQuyen;
        }
        if($this->InUsed !== null) {
            $conditions["InUsed"] = (bool)$this->InUsed;
        }
        return $conditions;
    }
}
