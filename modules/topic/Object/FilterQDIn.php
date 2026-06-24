<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;
use DateTime;
use MongoDB\BSON\Regex;

/**
 * @property ?int $ID_DV_QD
 * @property ?DateTime $startDate
 * @property ?DateTime $endDate
 * @property ?string $SoQD
 * @property ?bool $IsDeleted
 */
class FilterQDIn extends BaseObject {

    public ?int $ID_DV_QD = null;
    public ?DateTime $startDate = null;
    public ?DateTime $endDate = null;
    public ?string $SoQD = null;
    public ?bool $IsDeleted = null;


    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions() {
        $conditions = [];

        if($this->ID_DV_QD !== null && $this->ID_DV_QD > 0) {
            $conditions["ID_DV_QD"] = (int)$this->ID_DV_QD;
        }
        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }else{
            $conditions["IsDeleted"] = ['$ne' => true];
        }

        if($this->startDate !== null && $this->endDate !== null) {
            $from = clone $this->startDate;
            $to = clone $this->endDate;
            $from->setTime(0, 0, 0);
            $to->setTime(23, 59, 59);
            $conditions["NgayQD"] = ['$gte' => $from, '$lte' => $to];
        }

        if($this->SoQD !== null && $this->SoQD !== "") {
            $conditions["SoQD"] = ['$regex' => new Regex(preg_quote($this->SoQD, "/"), "ui")];
        }

        return $conditions;
    }
}
