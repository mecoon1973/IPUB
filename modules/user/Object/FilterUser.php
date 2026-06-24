<?php

namespace Modules\User\Object;

use Core\Object\BaseObject;

/**
 * @property ?bool $IsDeleted
 * @property ?int $IdNhom
 * @property ?int $_IdNhom
 * @property ?int $ID_DonVi
 * @property ?int $ID_DonVi
 * @property ?string $usernameSearch
 */
class FilterUser extends BaseObject {
    public ?bool $IsDeleted = null;
    public ?int $IdNhom = null;
    public ?int $_IdNhom = null;
    public ?int $ID_DonVi = null;
    public ?string $usernameSearch = null;


    public function __construct($input = []) {
        parent::__construct($input);

    }

    public function buildConditions() {
        $conditions = [];

        if($this->IsDeleted !== null) {
            $conditions["IsDeleted"] = (bool)$this->IsDeleted;
        }

        if ($this->IdNhom !== null) {
            $conditions['nhom_ids'] = (int) $this->IdNhom;
        }

        if($this->_IdNhom !== null) {
            $conditions["nhom_ids"] = ['$ne' => (int)$this->_IdNhom];
        }

        if($this->usernameSearch !== null) {
            if($this->usernameSearch == ""){
                $conditions["UserName"] = ['$ne' => ""];
            }else{
                $safePattern = preg_quote((string)$this->usernameSearch, '/');
                $conditions["UserName"] = ['$regex' => "/{$safePattern}/i"];
            }
        }

        if($this->ID_DonVi !== null) {
            $conditions["ID_DonVi"] = (int)$this->ID_DonVi;
        }
        return $conditions;
    }
}
