<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Tests\Feature\TestCase;
use App\Http\Controllers\HomeController;

class HomeControllerTest extends TestCase
{
    private HomeController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new HomeController();
        $this->container->set(HomeController::class, $this->controller);
        $this->router->get('/', HomeController::class);
    }

    public function testHomeEndpointReturnsSuccessfulResponse(): void
    {
        $request = $this->get('/');

        $response = $this->router->dispatch($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $body = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($body);
        $this->assertArrayHasKey('message', $body);
        $this->assertEquals('Hello, Fastify PHP!', $body['message']);
    }

    public function testNonExistentRouteReturns404(): void
    {
        $request = $this->get('/non-existent');

        $response = $this->router->dispatch($request);

        $this->assertEquals(404, $response->getStatusCode());
    }
}
