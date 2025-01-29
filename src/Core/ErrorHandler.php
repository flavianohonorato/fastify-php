<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;
use Nyholm\Psr7\Response;

class ErrorHandler
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $error): Response
    {
        $this->logger->error($error->getMessage(), [
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'trace' => $error->getTraceAsString()
        ]);

        if (php_sapi_name() !== 'cli') {
            http_response_code(500);
        }

        return new Response(
            500,
            ['Content-Type' => 'application/json'],
            json_encode([
                'error' => 'Internal Server Error',
                'message' => $error->getMessage(),
                'trace' => $error->getTraceAsString()
            ])
        );
    }
} 