<?php

namespace App\Controller\Note;

use App\Repository\GroupRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Services\Note\NoteManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NoteController extends AbstractController
{
    private NoteRepository $noteRepository;
    private UserRepository $userRepository;
    private GroupRepository $groupRepository;

    public function __construct(NoteRepository $noteRepository, UserRepository $userRepository, GroupRepository $groupRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @Route("/note", name="create note", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $res = json_decode($request->getContent());
            $note_management = new NoteManagement($this->noteRepository, $this->userRepository, $this->groupRepository);
            $note_management->create($res->group_name, $res->author, $res->format, $res->content);
            return new JsonResponse(
                [
                    'message' => 'Note created'
                ], 200
            );
        } catch (\Exception $exception) {
            $exception->getCode() === 0
                ? $code = 500
                : $code = $exception->getCode();
            return new JsonResponse(
                [
                    'message' => $exception->getMessage()
                ], $code
            );
        }

    }
}
