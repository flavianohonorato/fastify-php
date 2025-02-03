<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Controllers\ControllerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;
use RuntimeException;

class Router
{
    private array $routes = [];

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get(string $path, string $controller): void
    {
        $this->routes['GET'][$path] = $controller;
    }

    public function post(string $path, string $controller): void
    {
        $this->routes['POST'][$path] = $controller;
    }

    public function put(string $path, string $controller): void
    {
        $this->routes['PUT'][$path] = $controller;
    }

    public function delete(string $path, string $controller): void
    {
        $this->routes['DELETE'][$path] = $controller;
    }

    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        foreach ($this->routes[$method] as $route => $controller) {
            $pattern = preg_replace('/\{[^}]+}/', '([^/]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);

            if (preg_match('/^' . $pattern . '$/', $path, $matches)) {
                array_shift($matches);

                if (!$this->container->has($controller)) {
                    $this->container->set($controller, new $controller());
                }

                $controller = $this->container->get($controller);

                if (!$controller instanceof ControllerInterface) {
                    throw new RuntimeException("Controller must implement ControllerInterface");
                }

                return $controller->handle($request, ...$matches);
            }
        }

        if (isset($this->routes[$method][$path])) {
            $controllerClass = $this->routes[$method][$path];

            if (!$this->container->has($controllerClass)) {
                $this->container->set($controllerClass, new $controllerClass());
            }

            $controller = $this->container->get($controllerClass);

            if (!$controller instanceof ControllerInterface) {
                throw new RuntimeException("Controller must implement ControllerInterface");
            }

            return $controller->handle($request);
        }

        return new Response(404, [], 'Not Found');
    }
}
