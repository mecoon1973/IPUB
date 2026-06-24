<?php

namespace Modules\legalDeposit\Object;

use Core\Object\BaseObject;
use DateTime;

/**
 * @property ?bool $IsDeleted
 */
class FilterPhieuNhapLC extends BaseObject {
    public ?bool $IsDeleted = null;
    public ?string $TuKhoa = null;
    public ?DateTime $TuNgay = null;
    public ?DateTime $DenNgay = null;

    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }

        if($this->TuKhoa !== null && $this->TuKhoa !== "") {
            $conditions["TuKhoa"] = (string)$this->TuKhoa;
        }

        // if($this->TuNgay !== null && $this->DenNgay !== null) {
        //     $from = clone $this->TuNgay;
        //     $to = clone $this->DenNgay;
        //     $from->setTime(0, 0, 0);
        //     $to->setTime(23, 59, 59);
        //     $conditions["CreatedOn"] = ['$gte' => $from, '$lte' => $to];
        // }


        return $conditions;
    }
}
