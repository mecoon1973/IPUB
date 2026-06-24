<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;
use DateTime;
use MongoDB\BSON\Regex;

/**
 */
class FilterCT_QD_In extends BaseObject {


    public function __construct($input = []) {
        parent::__construct($input);
    }

    public function buildConditions() {
        $conditions = [];

        return $conditions;
    }
}
