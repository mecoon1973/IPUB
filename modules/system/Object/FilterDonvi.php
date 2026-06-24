<?php

namespace Modules\System\Object;

use Core\Object\BaseObject;


/**
 * @property ?bool $BienTap
 * @property ?bool $IsDeleted
 * @property ?bool $NoiBo
 * @property ?bool $NhaIn
 * @property ?bool $LienKet
 */
class FilterDonvi extends BaseObject {
    public ?bool $BienTap = null;
    public ?bool $IsDeleted = null;
    public ?bool $NhaIn = null;
    public ?bool $NoiBo = null;
    public ?bool $LienKet = null;


    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];
        if($this->BienTap !== null) {
            $conditions["BienTap"] = (bool)$this->BienTap;
        }
        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }
        if($this->NoiBo !== null) {
            $conditions["NoiBo"] = (bool)$this->NoiBo;
        }
        if($this->NhaIn !== null) {
            $conditions["NhaIn"] = (bool)$this->NhaIn;
        }
        if($this->LienKet !== null) {
            $conditions["LienKet"] = (bool)$this->LienKet;
        }
        return $conditions;
    }
}
