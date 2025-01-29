<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use Tests\TestCase;
use App\Core\Container;
use RuntimeException;
use stdClass;

class ContainerTest extends TestCase
{
    public function testContainerIsInitializedInSetUp(): void
    {
        $this->assertNotNull($this->container);
        $this->assertInstanceOf(Container::class, $this->container);
    }

    public function testCanSetAndGetInstance(): void
    {
        $instance = new stdClass();

        $this->container->set('test', $instance);

        $this->assertTrue($this->container->has('test'));
        $this->assertSame($instance, $this->container->get('test'));
    }

    public function testCanSetAndResolveCallback(): void
    {
        $callback = fn() => new stdClass();

        $this->container->set('test', $callback);

        $this->assertTrue($this->container->has('test'));
        $this->assertInstanceOf(stdClass::class, $this->container->get('test'));
    }

    public function testThrowsExceptionForNonExistentId(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No definition found for non-existent');

        $this->container->get('non-existent');
    }

    public function testCallbackReceivesContainerInstance(): void
    {
        $callback = function ($container) {
            $this->assertInstanceOf(Container::class, $container);
            return new stdClass();
        };

        $this->container->set('test', $callback);

        $this->container->get('test');
    }

    public function testReturnsSameInstanceOnMultipleGets(): void
    {
        $this->container->set('test', new stdClass());

        $first = $this->container->get('test');
        $second = $this->container->get('test');

        $this->assertSame($first, $second);
    }
}
