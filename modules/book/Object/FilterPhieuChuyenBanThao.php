<?php
namespace Modules\Book\Object;
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