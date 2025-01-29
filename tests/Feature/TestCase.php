<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase as BaseTestCase;
use App\Core\Router;
use App\Core\Logger;
use App\Core\ErrorHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;

abstract class TestCase extends BaseTestCase
{
    protected Router $router;
    protected Logger $logger;
    protected ErrorHandler $errorHandler;
    protected Psr17Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Psr17Factory();
        $this->logger = new Logger();
        $this->errorHandler = new ErrorHandler($this->logger);

        $this->container->set(Logger::class, $this->logger);
        $this->container->set(ErrorHandler::class, $this->errorHandler);

        $this->router = new Router($this->container);
    }

    protected function createRequest(
        string $method,
        string $uri,
        array $serverParams = [],
        array $headers = [],
        array $cookies = [],
        array $queryParams = [],
        ?array $parsedBody = null
    ): ServerRequest {
        return new ServerRequest(
            $method,
            $this->factory->createUri($uri),
            $headers,
            null,
            '1.1',
            $serverParams,
            $cookies,
            $queryParams,
            $parsedBody
        );
    }

    protected function get(string $uri, array $headers = []): ServerRequest
    {
        return $this->createRequest('GET', $uri, [], $headers);
    }

    protected function post(string $uri, array $data = [], array $headers = []): ServerRequest
    {
        return $this->createRequest('POST', $uri, [], $headers, [], [], $data);
    }
}
