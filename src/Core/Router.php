<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Controllers\ControllerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

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

    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        if (isset($this->routes[$method][$path])) {
            $controllerClass = $this->routes[$method][$path];

            if (!$this->container->has($controllerClass)) {
                $this->container->set($controllerClass, new $controllerClass());
            }

            $controller = $this->container->get($controllerClass);

            if (!$controller instanceof ControllerInterface) {
                throw new \RuntimeException("Controller must implement ControllerInterface");
            }

            return $controller->handle($request);
        }

        return new Response(404, [], 'Not Found');
    }
}
