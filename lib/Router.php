<?php

namespace IdkChat\Lib;

include_once "Singleton.php";

class Router extends Singleton {
    private array $routes = [];

    public function add_route(string $method, string $path, BaseRoute $route): void {
        if(!in_array($path, $this->routes))
            $this->routes[$path] = [];
        $this->routes[$path][$method] = $route;
    }

    public function get(string $path, BaseRoute $route): void {
        $this->add_route("GET", $path, $route);
    }

    public function post(string $path, BaseRoute $route): void {
        $this->add_route("POST", $path, $route);
    }

    public function put(string $path, BaseRoute $route): void {
        $this->add_route("PUT", $path, $route);
    }

    public function patch(string $path, BaseRoute $route): void {
        $this->add_route("PATCH", $path, $route);
    }

    public function delete(string $path, BaseRoute $route): void {
        $this->add_route("DELETE", $path, $route);
    }

    public function doTheThing(): string {
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $method = $_SERVER["REQUEST_METHOD"];

        if(!array_key_exists($path, $this->routes) || !array_key_exists($method, $this->routes[$path])) {
            http_response_code(404);
            return "";
        }

        $cls = $this->routes[$path][$method];
        return $cls->response();
    }
}