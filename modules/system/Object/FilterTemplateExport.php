<?php
namespace Modules\System\Object;
use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 *
 */
class FilterTemplateExport extends BaseObject {

    public ?string $key = null;
    public ?string $name = null;
    public ?string $path_file_template = null;
    public ?bool $is_deleted = null;

    public function __construct($input = []) {
        parent::__construct($input);
    }
    public function buildConditions() {
        $conditions = [];
        if ($this->key && !empty($this->key)) {
            $conditions["key"] = ['$regex' =>  new Regex(preg_quote($this->key, "/"), "ui")];
        }
        if ($this->name && !empty($this->name)) {
            $conditions['name'] = ['$regex' =>  new Regex(preg_quote($this->name, "/"), "ui")];
        }
        if ($this->path_file_template && !empty($this->path_file_template)) {
            $conditions['path_file_template'] = ['$regex' =>  new Regex(preg_quote($this->path_file_template, "/"), "ui")];
        }
        if ($this->is_deleted && !empty($this->is_deleted)) {
            $conditions['is_deleted'] = $this->is_deleted;
        }
        return $conditions;
    }
}
