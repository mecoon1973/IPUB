<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;


/**
 * @property ?bool $IsDeleted
 * @property ?string $macd
 * @property ?string $tencd
 */
class FilterCongdoan extends BaseObject {
    public ?bool $inused = null;
    public ?string $macd = null;
    public ?string $tencd = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions() {
        $conditions = [];

        if($this->inused !== null) {
            $conditions["inused"] = (bool)$this->inused;
        }

        if($this->macd !== null) {
            $conditions["macd"] = (string)$this->macd;
        }

        if($this->tencd !== null) {
            $conditions["tencd"] = (string)$this->tencd;
        }



        return $conditions;
    }
}
