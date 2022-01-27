<?php

namespace App\Exception;

class UserNotFound extends DomainException
{
    public function __construct(String $username)
    {
        parent::__construct('User "' . $username . '" doesn\'t exist. Please try again.', 404);
    }
}
