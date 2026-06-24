<?php
namespace Core\Facade;

use Illuminate\Support\Facades\Facade;

class Helper extends Facade {

    protected static function getFacadeAccessor() {
        return "base.helper";
    }

}