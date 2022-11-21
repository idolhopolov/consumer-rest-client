<?php

namespace Merce\Consumer\RestClient;

class Router
{

    private array $routes;

    public function register(string $route, array|callable $action)
    {

        $this->routes[$route] = $action;
        return $this;
    }

    public function resolve(string $requestUri)
    {

        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$route] ?? null;

        if (is_callable($action)) {
            return call_user_func($action);
        }
        [$class, $method] = $action;
        $class = new $class();

        if (method_exists($class, $method)) {
            return call_user_func([$class, $method], []);
        }
    }
}