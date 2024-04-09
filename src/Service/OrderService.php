<?php

namespace Service;

use Repository\OrderProductRepository;
use Repository\OrderRepository;
use Repository\Repository;
use Repository\UserProductRepository;


class OrderService
{

    private OrderRepository $orderModel;
    private OrderProductRepository $orderProductModel;
    private UserProductRepository $userProductModel;

    public function __construct(UserProductRepository $userProductModel, OrderRepository $orderModel, OrderProductRepository $orderProductModel)
    {
        $this->userProductModel = $userProductModel;
        $this->orderModel = $orderModel;
        $this->orderProductModel = $orderProductModel;
    }


    public function create(int $userId, string $firstname, string $lastname, string $country, string $city, string $address, int $postcode, int $phoneOrder, string $email): void
    {
        $pdo = Repository::getPdo();
        $pdo->beginTransaction();

        try {
            $this->orderModel->create($userId, $firstname, $lastname, $country, $city, $address, $postcode, $phoneOrder, $email);
            $orderId = $this->orderModel->getOrderId();
            $this->orderProductModel->create($userId, $orderId);
            $this->userProductModel->removeAllProducts($userId);

            $pdo->commit();


        } catch (\Throwable $exception) {
            $pdo->rollback();
            $exception->getMessage();
        }


    }
}