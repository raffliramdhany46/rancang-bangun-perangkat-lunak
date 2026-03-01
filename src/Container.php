<?php

declare(strict_types=1);

use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

/**
 * Container dependency sederhana untuk resolve class berbasis type-hint constructor.
 */
final class Container
{
    /** @var array<string, object> */
    private array $instances = [];

    public function set(string $id, object $instance): void
    {
        $this->instances[$id] = $instance;
    }

    public function get(string $id): object
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!class_exists($id)) {
            throw new RuntimeException("Class {$id} tidak ditemukan.");
        }

        $reflection = new ReflectionClass($id);
        $constructor = $reflection->getConstructor();

        if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
            $instance = $reflection->newInstance();
            $this->instances[$id] = $instance;

            return $instance;
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            $parameterType = $parameter->getType();

            if (!$parameterType instanceof ReflectionNamedType || $parameterType->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }

                $name = $parameter->getName();
                throw new RuntimeException("Dependency {$id}::\${$name} tidak dapat di-resolve otomatis.");
            }

            $arguments[] = $this->get($parameterType->getName());
        }

        $instance = $reflection->newInstanceArgs($arguments);
        $this->instances[$id] = $instance;

        return $instance;
    }
}

