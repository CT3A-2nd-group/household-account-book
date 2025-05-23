<?php
class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        if (isset($this->routes[$method][$path])) {
            $this->routes[$method][$path]();
        } else {
            http_response_code(404);
            echo 'ページが見つかりません。';
        }
    }
}
