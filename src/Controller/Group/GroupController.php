<?php

namespace App\Controller\Group;

use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Services\Admin\Admin;
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
            $group->create($res->name, $res->username);
            return new JsonResponse(
                [
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
     * @Route("/group", name="delete_group", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $res = json_decode($request->getContent());
            $group = new GroupManagement($this->groupRepository, $this->userRepository);
            $group->delete($res->name, $res->username);
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
     * @Route("/group/{group_id}", name="delete_group", methods={"GET"})
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
     * @Route("/groups/{username}", name="delete_group", methods={"GET"})
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
     * @Route("/user/{userId}/group/{groupId}/leave", name="leave_group", methods={"DELETE"})
     */
    public function leave(Request $request): JsonResponse
    {
        $groupId = $request->get('groupId');
        $userId = $request->get('userId');
        $eUser = $this->userRepository->find($userId);
        $username = $eUser->getUsername();
        $eGroup = new GroupManagement($this->groupRepository, $this->userRepository);
        $eGroup->removeParticipantsInAGroup($groupId, $username);

        //verif si c'est l'admin qui leave
        if ($admin_id = $userId){
        $eGroup = $this->groupRepository->find($groupId);
        $admin_id = $eGroup->getAdmin()->getId();
        $participants = $eGroup->getParticipants();
        foreach ($participants as $participant) {
            $particpantsInArray = $participants->toArray();
            $random = array_rand($particpantsInArray, 1);
            $newAdminId = $participants[$random];
            }
            $admin = new Admin($this->groupRepository, $this->userRepository);
            $admin->changeAdmin($newAdminId, $groupId);
        }
        return new JsonResponse(
            [
                'message' => 'Group left'
            ], 200
        );
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
