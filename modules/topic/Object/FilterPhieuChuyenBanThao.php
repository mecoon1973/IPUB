<?php
namespace Modules\Topic\Object;
use Core\Object\BaseObject;
/**
 *
 */
class FilterPhieuChuyenBanThao extends BaseObject {
    public function __construct($input = []) {
        parent::__construct($input);
    }
    public function buildConditions() {
        $conditions = [];
        return $conditions;
    }
}