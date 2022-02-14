<?php

namespace App\Exception;

class NoteAlreadyExist extends DomainException
{
    public function __construct()
    {
        parent::__construct('Note already exist.', 404);
    }
}
