<?php
namespace Modules\System\Object;
use Core\Object\BaseObject;
/**
 *
 */
class FilterCanboQuyen extends BaseObject {
    public function __construct($input = []) {
        parent::__construct($input);
    }
    public function buildConditions() {
        $conditions = [];
        return $conditions;
    }
}