<?php

namespace App\Services\Admin;


use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;

class Admin
{

    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    public function changeAdmin($userId, $groupId)
    {
        $eGroup = $this->groupRepository->find($groupId);
        $user = $this->userRepository->findById($userId);
//        dd($user[0]);
        $this->groupRepository->changeAdmin($groupId, $user[0]);
    }
}
