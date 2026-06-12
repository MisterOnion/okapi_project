<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class DuplicateLeadException extends Exception
{
    public function __construct()
    {
        parent::__construct('This lead is a duplicate and exist in DB');
    }
}