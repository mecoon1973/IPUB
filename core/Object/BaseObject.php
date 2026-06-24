<?php

namespace Core\Object;


abstract class BaseObject {

    /** các mối quan hệ cần load */
    public $relations = [];
    /** lấy các trường cần load */
    public $fields = [];
    /** số lượng bản ghi tối đa trả về */
    public $limit = 1500;

    public function __construct($input = []) {
        $attrArr = array_keys(get_object_vars($this));
        foreach ($attrArr as $attr) {
            if(isset($input[$attr])) {
                $this->$attr = $input[$attr];
            }
        }

    }

    public function buildConditions() {
        return [];
    }

    public function getRelations() : string {
        return implode(",", $this->relations);
    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function toJson() {
        return json_encode($this->toArray());
    }

    public function merge(array $data) {
        return array_merge($this->toArray(), $data);
    }


}
