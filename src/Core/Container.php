<?php

declare(strict_types=1);

namespace App\Core;

use Psr\Container\ContainerInterface;
use RuntimeException;

class Container implements ContainerInterface
{
    private array $instances = [];

    private array $definitions = [];

    public function get(string $id)
    {
        if ($this->has($id)) {
            if (isset($this->instances[$id])) {
                return $this->instances[$id];
            }

            $definition = $this->definitions[$id];
            if (is_callable($definition)) {
                $this->instances[$id] = $definition($this);
            } else {
                $this->instances[$id] = $definition;
            }

            return $this->instances[$id];
        }

        throw new RuntimeException("No definition found for $id");
    }

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }

    public function set(string $id, $concrete): void
    {
        $this->definitions[$id] = $concrete;
    }
}