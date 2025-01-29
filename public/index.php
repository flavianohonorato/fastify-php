<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Container;
use App\Core\Router;
use App\Core\Logger;
use App\Core\ErrorHandler;
use App\Http\Controllers\HomeController;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

$container = new Container();

$logger = new Logger();
$errorHandler = new ErrorHandler($logger);

$container->set(Logger::class, $logger);
$container->set(ErrorHandler::class, $errorHandler);
$container->set(HomeController::class, new HomeController());

$router = new Router($container);

$router->get('/', HomeController::class);

try {
    $psr17Factory = new Psr17Factory();

    $creator = new ServerRequestCreator(
        $psr17Factory, // ServerRequestFactory
        $psr17Factory, // UriFactory
        $psr17Factory, // UploadedFileFactory
        $psr17Factory  // StreamFactory
    );

    $request = $creator->fromGlobals();

    $logger->info('Processing request', [
        'method' => $request->getMethod(),
        'uri' => $request->getUri()->getPath()
    ]);

    $response = $router->dispatch($request);

    http_response_code($response->getStatusCode());

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }

    echo $response->getBody();

} catch (Throwable $e) {
    $logger->error('Fatal error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);

    $errorResponse = $errorHandler->handle($e);

    http_response_code($errorResponse->getStatusCode());

    foreach ($errorResponse->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }

    echo $errorResponse->getBody();
}
