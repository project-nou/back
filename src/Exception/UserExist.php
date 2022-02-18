<?php

namespace App\Exception;

class UserExist extends DomainException
{
    public function __construct(String $username)
    {
        parent::__construct('User "' . $username . '" already exist.', 404);
    }
}
