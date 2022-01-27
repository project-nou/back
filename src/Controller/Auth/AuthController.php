<?php

namespace App\Controller\Auth;

use App\Repository\UserRepository;
use App\Services\Auth\AuthManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/sign-in", name="login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        $res = json_decode($request->getContent());
        $username = $res->username;
        $password = $res->password;
        return new JsonResponse(
            [
                'message' => 'User is connected'
            ], 200
        );
    }

    /**
     * @Route("/sign-up", name="register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $res = json_decode($request->getContent());
            $auth = new AuthManagement($this->userRepository);
            $auth->register($res->password, $res->email, $res->username);
            return new JsonResponse(
                [
                    'message' => 'User is registered'
                ], 200
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'message' => "Error: " . $e->getMessage()
                ], 500
            );
        }

    }
}
