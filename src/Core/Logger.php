<?php

declare(strict_types=1);

namespace App\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

class Logger
{
    private MonologLogger $logger;

    public function __construct()
    {
        $this->logger = new MonologLogger('app');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Level::Debug));
        $this->logger->pushHandler(new StreamHandler('php://stdout', Level::Debug));
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }
} 