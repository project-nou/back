<?php

namespace App\Controller\Note;

use App\Repository\GroupRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Services\FileSystem\FileSystem;
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
            $note_management = new NoteManagement($this->noteRepository, $this->userRepository, $this->groupRepository);
            if ($request->get('format') === 'file') {
                $note_management->create($request->get('group'), $request->get('author'), $request->get('format'), $request->files->get('file')->getClientOriginalName());
                FileSystem::upload(file_get_contents($request->files->get('file')), $request->get('group'), $request->files->get('file')->getClientOriginalName());
            } else if ($request->get('format') === 'file') {
                $note_management->create($request->get('group'), $request->get('author'), $request->get('format'), $request->get('content'));
            }

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

    /**
     * @Route("/notes/{group_id}", name="create note", methods={"GET"})
     */
    public function getAllNotesByGroup(Request $request): JsonResponse
    {
        try {
            $note_management = new NoteManagement($this->noteRepository, $this->userRepository, $this->groupRepository);

            return new JsonResponse(
                [
                    'notes' => $note_management->getAllNotesByGroup($request->get('group_id')),
                    'message' => 'Notes of the group get'
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
