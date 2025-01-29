<?php

namespace App\Core;

use Revolt\EventLoop;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use App\Core\Router;

class Server
{
    public function __construct(private Router $router)
    {
    }

    public function start()
    {
        $socket = stream_socket_server('tcp://0.0.0.0:8080', $errno, $errstr);

        if (!$socket) {
            die("$errstr ($errno)\n");
        }

        echo "Server started at http://localhost:8080\n";

        EventLoop::onReadable($socket, function ($socket) {
            $client = stream_socket_accept($socket, -1);

            if ($client) {
                $requestData = fread($client, 1024);
                $response = $this->handleRequest($requestData);
                fwrite($client, $response);
                fclose($client);
            }
        });

        EventLoop::run();
    }

    private function handleRequest(false|string $requestData): string
    {
        $psr7Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr7Factory,
            $psr7Factory,
            $psr7Factory,
            $psr7Factory
        );

        $request = $creator->fromGlobals();
        $response = $this->router->dispatch($request);

        return sprintf(
            "HTTP/1.1 %s %s\r\nContent-Length: %d\r\n\r\n%s",
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            strlen($response->getBody()),
            $response->getBody()
        );
    }
}
