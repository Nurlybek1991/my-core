<?php

namespace Service\AuthenticationService;

use Entity\User;
use Repository\UserRepository;

class AuthenticationCookieService implements AuthenticationServiceInterface
{
    private UserRepository $userModel;

    public function __construct(UserRepository $userModel)
    {
        $this->userModel = $userModel;
    }

    public function check(): bool
    {
        if (empty($_COOKIE['user_id'])) {
            session_start();
        }

        return isset($_COOKIE['user_id']);
    }

    public function getCurrentUser(): User|null
    {
        if ($this->check()) {
            $userId = $_COOKIE['user_id'];

            return $this->userModel->getById($userId);
        }

        return null;
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userModel->getByEmail($email);

        if (!$user instanceof User){
            return false;
        }

        if (!password_verify($password, $user->getPassword())){
            return false;

        }

        setcookie('user_id', $user->getId());
        return true;
    }
    public function logout(): void
    {
        if ($this->check()) {

            setcookie('user_id', '', time() - 3600, '/');
        }
    }

}