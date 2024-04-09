<?php

use Controller\CartController;
use Controller\MainController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;
use Core\Container;
use Repository\OrderProductRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Repository\UserRepository;
use Service\AuthenticationService\AuthenticationServiceInterface;
use Service\AuthenticationService\AuthenticationSessionService;
use Service\CartService;
use Service\OrderService;

return [

    UserController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $userModel = new UserRepository();

        return new UserController($authenticationService, $userModel);
    },

    ProductController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $cartService = $container->get(CartService::class);

        return new ProductController($authenticationService, $cartService);
    },

    MainController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $cartService = $container->get(CartService::class);
        $productModel = new ProductRepository();

        return new MainController($authenticationService, $cartService, $productModel);
    },

    CartController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $cartService = $container->get(CartService::class);
        $userProductModel = $container->get(UserProductRepository::class);

        return new CartController($authenticationService, $cartService, $userProductModel);
    },

    OrderController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $cartService = $container->get(CartService::class);
        $orderService = $container->get(OrderService::class);

        return new OrderController($authenticationService, $cartService, $orderService);
    },

    CartService::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $userProductModel = $container->get(UserProductRepository::class);

        return new CartService($authenticationService, $userProductModel);
    },

    OrderService::class => function (Container $container) {
        $userProductModel = new UserProductRepository();
        $orderModel = $container->get(OrderRepository::class);
        $orderProductModel = $container->get(OrderProductRepository::class);

        return new OrderService($userProductModel, $orderModel, $orderProductModel);
    },

    AuthenticationServiceInterface::class => function (Container $container) {
        $userModel = $container->get(UserRepository::class);

        return new AuthenticationSessionService($userModel);

    }

];