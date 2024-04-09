<?php

namespace Core;

class Container
{
    private array $services;

    public function __construct(array $services){
        $this->services = $services;
    }

    public function set($className, callable $callback): void
    {
        $this->services[$className] = $callback;
    }

    public function get(string $className): object
    {
        if (isset($this->services[$className])) {
            $callback = $this->services[$className];
            return $callback($this);
        }

        return new $className();

    }
}