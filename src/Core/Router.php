<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable $callback): void
    {
        $this->routes[] = [$method, $path, $callback];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as [$routeMethod, $routePath, $callback]) {
            if ($method === $routeMethod && $uri === $routePath) {
                $callback();
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}
