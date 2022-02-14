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

    public function getOne(int $group_id): ?\App\Entity\Group
    {
        return $this->groupRepository->find($group_id);
    }

    public function getAllByUsername(string $username): array
    {
        $user = $this->userRepository->findOneByUsername($username);
        $groups = [];
        foreach ($user->getGroupsTheirIn() as $group) {
            $temp['group_id'] = $group->getId();
            $temp['group_name'] = $group->getName();
            $temp['author_id'] = $group->getAdmin()->getId();
            $temp['author'] = $group->getAdmin()->getUsername();
            array_push($groups, $temp);
        }
        return $groups;
    }

    public function getNames(int $group_id, int $user_id) : array
    {
        return [
            "group_name" => $this->groupRepository->getNameById($group_id),
            "username" => $this->userRepository->getUsernameById($user_id),
        ];
    }

    public function update(int $group_id, string $group_name)
    {
        $this->groupRepository->update($group_id, $group_name);
    }
}
