<?php

declare(strict_types=1);

/**
 * Pengujian container: auto resolve dependency constructor dan cache instance.
 */
final class ContainerTest extends TestCase
{
    public function testResolvesNestedDependencies(): void
    {
        $container = new Container();
        $service = $container->get(ContainerTestService::class);

        $this->assertTrue($service instanceof ContainerTestService);
        $this->assertSame('ready', $service->status());
    }

    public function testReturnsSameInstanceForSameClass(): void
    {
        $container = new Container();
        $first = $container->get(ContainerTestService::class);
        $second = $container->get(ContainerTestService::class);

        $this->assertTrue($first === $second, 'Container seharusnya cache instance class yang sama.');
    }

    public function testThrowsWhenClassNotFound(): void
    {
        $container = new Container();

        $this->assertThrows(
            static fn (): object => $container->get('KelasTidakAda123'),
            RuntimeException::class
        );
    }
}

final class ContainerTestDependency
{
    public function ping(): string
    {
        return 'ready';
    }
}

final class ContainerTestService
{
    private ContainerTestDependency $dependency;

    public function __construct(ContainerTestDependency $dependency)
    {
        $this->dependency = $dependency;
    }

    public function status(): string
    {
        return $this->dependency->ping();
    }
}

