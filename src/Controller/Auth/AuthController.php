<?php

namespace App\Controller\Auth;

use App\Services\FileSystem\FileSystem;
use Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController
{
    /**
     * @Route("/sign-in", name="login", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'User is connected'
            ], 200
        );
    }
}
