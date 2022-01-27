<?php

namespace App\Services\Auth;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;

class AuthManagement
{

    private UserRepository $userRepository;
    private string $secret_key;

    public function __construct(UserRepository $userRepository, string $secret_key)
    {
        $this->userRepository = $userRepository;
        $this->secret_key = $secret_key;
    }

    public function register(string $password, string $email, string $username) : void
    {
        $this->userRepository->register(self::encodePassword($password), $email, $username);
    }

    public function login(string $username, string $password) : string
    {
        if (self::ensurePasswordIsValid($password, self::encodePassword($password))) {
            $this->userRepository->login($username);
        }
        return self::encodeToken($this->secret_key,
            [
                $username,
                "iat" => time(),
                "exp" => time() + 60 * 60
            ]);
    }


    private static function encodePassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private static function ensurePasswordIsValid(string $password, string $encodePassword): bool
    {
        return password_verify($password, $encodePassword);
    }

    private static function encodeToken(string $key, array $payload): string
    {
        return JWT::encode($payload, $key, 'HS256');
    }
}
