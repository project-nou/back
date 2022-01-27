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
        $res = json_decode($request->getContent());
        $group = new GroupManagement($this->groupRepository, $this->userRepository);
        $group->create($res->name, $res->username);
        return new JsonResponse(
            [
                'message' => 'Group is created'
            ], 200
        );
    }

    /**
     * @Route("/group", name="delete_group", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        $res = json_decode($request->getContent());
        $group = new GroupManagement($this->groupRepository, $this->userRepository);
        $group->delete($res->name, $res->username);
        return new JsonResponse(
            [
                'message' => 'Group is deleted'
            ], 200
        );
    }

    /**
     * @Route("/{group_name}/add/{username}", name="add user in group", methods={"POST"})
     */
    public function addParticipants(Request $request): JsonResponse
    {
        $group_name = $request->get('group_name');
        $username = $request->get('username');
        $group = new GroupManagement($this->groupRepository, $this->userRepository);
        $group->addParticipantInAGroup($group_name, $username);
        return new JsonResponse(
            [
                'message' => "$username has been added to $group_name group"
            ], 200
        );
    }
}
