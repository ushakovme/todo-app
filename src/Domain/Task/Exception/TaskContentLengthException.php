<?php

namespace App\Domain\Task\Exception;

use App\Domain\DomainException\DomainException;
use App\Domain\Task\Task;

class TaskContentLengthException extends DomainException
{
    public $message = 'Task content max length is exceeded. Max length is ' . Task::MAX_TASK_LENGTH;
}
