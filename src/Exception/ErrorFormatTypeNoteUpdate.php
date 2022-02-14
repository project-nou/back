<?php

namespace App\Exception;

class ErrorFormatTypeNoteUpdate extends DomainException
{
    public function __construct()
    {
        parent::__construct('Note format file cannot be update.', 404);
    }
}
