<?php
        namespace Modules\LegalDeposit\Object;
        use Core\Object\BaseObject;
        /**
         *
         */
        class FilterToKhaiLuuChuyen extends BaseObject {
            public function __construct($input = []) {
                parent::__construct($input);
            }
            public function buildConditions() {
                return $conditions;
            }
        }