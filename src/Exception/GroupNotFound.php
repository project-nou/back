<?php

namespace App\Exception;

class GroupNotFound extends DomainException
{
    public function __construct(String $name)
    {
        parent::__construct('Group "' . $name . '" doesn\'t exist. Please try again.', 404);
    }
}
