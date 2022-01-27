<?php

namespace App\Services\Auth;

use App\Repository\UserRepository;

class AuthManagement
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(string $password, string $email, string $username) : void
    {
        $this->userRepository->register(self::encodePassword($password), $email, $username);
    }


    private static function encodePassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private static function ensurePasswordIsValid(string $password, string $encodePassword): bool
    {
        return password_verify($password, $encodePassword);
    }
}
