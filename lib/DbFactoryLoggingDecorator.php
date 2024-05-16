<?php

namespace IdkChat\Lib;

use IdkChat\Database\Models\BaseFactory;

class DbFactoryLoggingDecorator implements BaseFactory {
    private $factoryCls;

    function __construct($factoryCls) {
        $this->factoryCls = $factoryCls;
    }

    public function create() {
        $params = "";
        $args = func_get_args();
        foreach ($args as $arg) {
            if(strlen($params))
                $params .= ", ";
            $params .= "".$arg;
        }

        error_log("Creating object via $this->factoryCls with following parameters: ($params)");
        return $this->factoryCls::create(...func_get_args());
    }
}