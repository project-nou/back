<?php

namespace App\Controller\Group;

use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Services\Group\GroupManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/group", name="create_group", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $res = json_decode($request->getContent());
            $group = new GroupManagement($this->groupRepository, $this->userRepository);
            $group = $group->create($res->name, $res->username);
            return new JsonResponse(
                [
                    'groupname' =>$group->getName(),
                    'group_id' => $group->getId(),
                    'message' => 'Group is created'
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
     * @Route("/group/{group_id}/{username}", name="delete_group", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $group_id = $request->get('group_id');
        $username = $request->get('username');
        try {
            $group = new GroupManagement($this->groupRepository, $this->userRepository);
            $group->delete($group_id, $username);
            return new JsonResponse(
                [
                    'message' => 'Group is deleted'
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
     * @Route("/group/{group_id}", name="get_one__group", methods={"GET"})
     */
    public function getOne(Request $request): JsonResponse
    {
        try {
            $group_id = $request->get('group_id');
            $group = new GroupManagement($this->groupRepository, $this->userRepository);
            return new JsonResponse(
                [
                    'name' => $group->getOne($group_id)->getName(),
                    'group_id' => $group->getOne($group_id)->getId(),
                    'admin' => $group->getOne($group_id)->getAdmin()->getUsername(),
                    'participants' => $group->getOne($group_id)->getParticipants(),
                    'notes' => $group->getOne($group_id)->getNotes(),
                    'message' => 'Group is get'
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
     * @Route("/groups/{username}", name="get all_group", methods={"GET"})
     */
    public function getAllByUsername(Request $request): JsonResponse
    {
        try {
            $username = $request->get('username');
            $group = new GroupManagement($this->groupRepository, $this->userRepository);
            return new JsonResponse(
                [
                    'groups' => $group->getAllByUsername($username),
                    'message' => 'Groups get'
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
     * @Route("/group", name="update_group", methods={"PATCH"})
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $group_id = json_decode($request->getContent())->group_id;
            $group_name = json_decode($request->getContent())->group_name;
            $group = new GroupManagement($this->groupRepository, $this->userRepository);
            $group->update($group_id, $group_name);
            return new JsonResponse(
                [
                    'message' => 'Group is updated'
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
     * @Route("/{group_name}/add/{username}", name="add user in group", methods={"POST"})
     */
    public function addParticipants(Request $request): JsonResponse
    {
        try {
            $group_name = $request->get('group_name');
            $username = $request->get('username');
            $group = new GroupManagement($this->groupRepository, $this->userRepository);
            $group->addParticipantInAGroup($group_name, $username);
            return new JsonResponse(
                [
                    'message' => "$username has been added to $group_name group"
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
