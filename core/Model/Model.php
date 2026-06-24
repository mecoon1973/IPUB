<?php
namespace Core\Model;

use Closure;
use MongoDB\Laravel\Eloquent\Model as BaseModel;
use Core\Traits\CustomHybridRelations;

/**
 * @property string $_id
 */
abstract class Model extends BaseModel {

    use CustomHybridRelations;
    private $__extra_data = [];

    public function setListAttribute(array $dataAttribute) {
        foreach ($dataAttribute as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    protected function getField($field, $callBack) {
        if(!array_key_exists($field, $this->__extra_data)) {
            if(is_string($callBack)) {
                $this->__extra_data[$field] = $this->$callBack();
            } elseif ($callBack instanceof Closure) {
                $this->__extra_data[$field] = $callBack();
            }
        }
        return $this->__extra_data[$field];
    }
}
