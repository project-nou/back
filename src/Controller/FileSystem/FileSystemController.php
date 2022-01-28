<?php

namespace App\Controller\FileSystem;

use App\Services\FileSystem\FileSystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FileSystemController
{
    /**
     * @Route("/file", name="upload_file", methods={"POST"})
     */
    public function upload(Request $request): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'Upload file ok'
            ], 200
        );
    }

    /**
     * @Route("/file", name="delete_file", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        $res = json_decode($request->getContent());
        FileSystem::delete($res->group, $res->filename);
        return new JsonResponse(
            [
                'message' => 'Delete file ok'
            ], 200
        );
    }
}
