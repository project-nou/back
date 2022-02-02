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
            } else if ($request->get('format') === 'text') {
                $note_management->create($request->get('group'), $request->get('author'), $request->get('format'), $request->get('content'));
            }
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

    /**
     * @Route("/notes/{group_id}", name="get all notes by group", methods={"GET"})
     * Get all notes by type of note (text or file)
     */
    public function getAllNotesByGroup(Request $request): JsonResponse
    {
        try {
            $note_management = new NoteManagement($this->noteRepository, $this->userRepository, $this->groupRepository);
            return new JsonResponse(
                [
                    'notes' => $note_management->getAllNotesByGroup($request->get('group_id'), json_decode($request->getContent())->type_note),
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

    /**
     * @Route("/note", name="delete note of a group", methods={"DELETE"})
     * Get all notes by type of note (text or file)
     */
    public function delete(Request $request): JsonResponse
    {
        $group_id = json_decode($request->getContent())->group_id;
        $note_id = json_decode($request->getContent())->note_id;
        try {
            $note_management = new NoteManagement($this->noteRepository, $this->userRepository, $this->groupRepository);
            $note_management->delete($group_id, $note_id);
            return new JsonResponse(
                [
                    'message' => 'Note is deleted'
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
