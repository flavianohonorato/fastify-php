# Fastify PHP

A lightweight PHP framework inspired by Fastify, implementing PSR-7, PSR-11, and PSR-15 standards. Built with modern PHP practices and focusing on simplicity and performance.

## Features

- **PSR-7 HTTP Message Implementation** using Nyholm PSR-7
- **PSR-11 Container Implementation** for Dependency Injection
- **Modern Error Handling** with structured JSON responses
- **Comprehensive Logging System** using Monolog
- **Clean Architecture** following SOLID principles
- **Type-Safe** with strict typing enabled

## Requirements

- PHP 8.2 or higher
- Composer

## Installation

```bash
composer install
```

## Quick Start

1. Start the server:
```bash
php -S localhost:8000 -t public
```

2. Visit `http://localhost:8000` in your browser or use curl:
```bash
curl http://localhost:8000
```

## Project Structure

```
.
├── public/
│   └── index.php            # Application entry point
├── src/
│   ├── Core/                # Core framework components
│   │   ├── Container.php    # PSR-11 DI container
│   │   ├── Router.php       # HTTP router
│   │   ├── Logger.php       # Logging service
│   │   └── ErrorHandler.php # Global error handler
│   └── Http/
│       └── Controllers/     # Application controllers
├── logs/                    # Application logs
├── composer.json            # Dependencies and autoloading
└── README.md                # This file
```

## Core Components

### Container (PSR-11)
- Implements PSR-11 ContainerInterface
- Supports dependency injection
- Manages service lifecycle

### Router
- Supports GET and POST methods
- Route parameters (coming soon)
- Controller-based handling

### Error Handler
- Structured JSON error responses
- Detailed logging
- Stack trace in development

### Logger
- File and console logging
- Different log levels (debug, info, error)
- Context-aware logging

## Controllers

Controllers must implement the `ControllerInterface`:

```php
namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
```

## Example Controller

```php
namespace App\Http\Controllers;

use Nyholm\Psr7\Response;

class HomeController implements ControllerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => 'Hello, Fastify PHP!'])
        );
    }
}
```

## Upcoming Features

### Testing & Quality
- [ ] PHPUnit integration with test examples
- [ ] Feature and Unit test base classes
- [ ] Mock and stub helpers for testing
- [ ] Code coverage reports
- [ ] Static analysis with PHPStan

### Core Features
- [ ] Route parameters and pattern matching
- [ ] Middleware support (before/after handlers)
- [ ] Request validation with custom rules
- [ ] Database integration with query builder
- [ ] Configuration management
- [ ] CLI commands support

### Performance & Development
- [ ] Development server with auto-reload
- [ ] Route caching for production
- [ ] Configuration caching
- [ ] Development debug toolbar
- [ ] Performance profiling tools
- [ ] OpenAPI/Swagger documentation
- [ ] CORS middleware
- [ ] Rate limiting middleware

### Security
- [ ] CSRF protection
- [ ] XSS prevention helpers
- [ ] Request throttling

### Developer Experience
- [ ] Database migrations
- [ ] Seeders for test data
- [ ] Make commands for scaffolding
- [ ] Environment-based configuration
- [ ] Pretty error pages in development
- [ ] Detailed logging with context

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'feat: add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
