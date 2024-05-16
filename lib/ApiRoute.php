<?php

namespace IdkChat\Lib;

include_once "BaseRoute.php";

use Closure;

class ApiRoute implements BaseRoute {
    private Closure $func;

    public function __construct(Closure $func) {
        $this->func = $func;
    }

    public function response(): string {
        ob_start();

        $func = $this->func;
        $func();
        return ob_get_clean();
    }
}