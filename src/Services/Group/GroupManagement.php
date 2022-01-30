<?php

namespace App\Services\Group;

use App\Exception\UserNotFound;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;

class GroupManagement
{

    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function create(string $name, string $username) :void
    {
        $user = $this->userRepository->findOneByUsername($username);
        !$user
            ? throw new UserNotFound($username)
            : $this->groupRepository->create($name, $user);
    }

    public function addParticipantInAGroup(string $name, string $username) :void
    {
        $user = $this->userRepository->findOneByUsername($username);
        !$user
            ? throw new UserNotFound($username)
            : $this->groupRepository->addParticipant($name, $user);
    }

    public function delete(string $name, string $username) :void
    {
        $user = $this->userRepository->findOneByUsername($username);
        $this->groupRepository->remove($name, $user);
    }

    public function getNames(int $group_id, int $user_id) : array
    {
        return [
            "group_name" => $this->groupRepository->getNameById($group_id),
            "username" => $this->userRepository->getUsernameById($user_id),
        ];
    }
}
