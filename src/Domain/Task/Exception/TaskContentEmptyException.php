<?php

namespace App\Domain\Task\Exception;

use App\Domain\DomainException\DomainException;

class TaskContentEmptyException extends DomainException
{
    public $message = 'Task content can not be empty';
}
