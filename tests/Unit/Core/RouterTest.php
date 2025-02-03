<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use App\Core\Container;
use App\Core\Router;
use App\Http\Controllers\HomeController;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class RouterTest extends TestCase
{
    public function testRouteWithParameters()
    {
        $container = new Container();
        $container->set(HomeController::class, new HomeController());
        $router = new Router($container);

        $router->get('/user/{id}', HomeController::class);

        $request = new ServerRequest('GET', '/user/123');
        $response = $router->dispatch($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Hello, Fastify PHP!', 'params' => ['123']]),
            (string) $response->getBody()
        );
    }

    public function testPostRouteWithParameters()
    {
        $container = new Container();
        $container->set(HomeController::class, new HomeController());
        $router = new Router($container);

        $router->post('/user/{id}', HomeController::class);

        $request = new ServerRequest('POST', '/user/123');
        $response = $router->dispatch($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Hello, Fastify PHP!', 'params' => ['123']]),
            (string) $response->getBody()
        );
    }

    public function testPutRouteWithParameters()
    {
        $container = new Container();
        $container->set(HomeController::class, new HomeController());
        $router = new Router($container);

        $router->put('/user/{id}', HomeController::class);

        $request = new ServerRequest('PUT', '/user/123');
        $response = $router->dispatch($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Hello, Fastify PHP!', 'params' => ['123']]),
            (string) $response->getBody()
        );
    }

    public function testDeleteRouteWithParameters()
    {
        $container = new Container();
        $container->set(HomeController::class, new HomeController());
        $router = new Router($container);

        $router->delete('/user/{id}', HomeController::class);

        $request = new ServerRequest('DELETE', '/user/123');
        $response = $router->dispatch($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Hello, Fastify PHP!', 'params' => ['123']]),
            (string) $response->getBody()
        );
    }
}
