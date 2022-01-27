<?php

namespace App\Services\Note;

use App\Exception\GroupNotFound;
use App\Exception\UserNotFound;
use App\Repository\GroupRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;

class NoteManagement
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

    public function create(string $group_name, string $author, string $format, string $content)
    {
        if(!$this->userRepository->findOneByUsername($author)) throw new UserNotFound($author);
        if(!$this->groupRepository->findOneByName($group_name)) throw new GroupNotFound($group_name);
        $this->noteRepository->create($this->userRepository->findOneByUsername($author), $this->groupRepository->findOneByName($group_name), $format, $content);
    }
}
