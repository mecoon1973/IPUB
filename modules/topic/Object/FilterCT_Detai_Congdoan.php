<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;


/**
 * @property ?int $IDDeTai
 * @property ?int $IDSach
 * @property ?string $MaCD

 */
class FilterCT_Detai_Congdoan extends BaseObject {
    public ?int $IDDeTai = null;
    public ?int $IDSach = null;
    public ?string $MaCD = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions() {
        $conditions = [];

        if($this->IDDeTai !== null) {
            $conditions["IDDeTai"] = (int)$this->IDDeTai;
        }

        if($this->IDSach !== null) {
            $conditions["IDSach"] = (int)$this->IDSach;
        }

        if($this->MaCD !== null) {
            $conditions["MaCD"] = (string)$this->MaCD;
        }



        return $conditions;
    }
}
