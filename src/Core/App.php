<?php

namespace Core;

use Request\Request;
use Service\LoggerService;

class App
{
    private Container $container;
    private LoggerService $loggerService;
    private array $routes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->loggerService = $this->container->get(LoggerService::class);
    }

    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (isset($this->routes[$uri])) {
            $routeMethods = $this->routes[$uri];
            $routeMethod = $_SERVER['REQUEST_METHOD'];

            if (isset($routeMethods[$routeMethod])) {
                $handler = $routeMethods[$routeMethod];
                $class = $handler['class'];
                $method = $handler['method'];

                if (isset($handler['request'])) {
                    $requestClass = $handler['request'];
                    $request = new $requestClass($routeMethod, $_POST);
                } else {
                    $request = new Request($routeMethod, $_POST);
                }

                $obj = $this->container->get($class);

                try {
                    $obj->$method($request);
                } catch (\Throwable $exception) {

                    $this->loggerService->error($exception);

                    require_once './../View/500.html';
                }
            } else {
                echo "$routeMethod не поддерживается для адреса $uri!";
            }
        } else {
            require_once './../View/404.html';
        }

    }

    public function get($routeName, $className, $method, $request = null): void
    {
        $this->routes[$routeName]['GET'] = [
            'class' => $className,
            'method' => $method,
            'request' => $request
        ];
    }

    public function post($routeName, $className, $method, $request = null): void
    {
        $this->routes[$routeName]['POST'] = [
            'class' => $className,
            'method' => $method,
            'request' => $request
        ];
    }


}