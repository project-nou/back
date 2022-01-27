<?php

namespace App\Exception;

class GroupExist extends DomainException
{
    public function __construct(string $name)
    {
        parent::__construct('Group "' . $name . '" already exist.', 404);
    }
}
