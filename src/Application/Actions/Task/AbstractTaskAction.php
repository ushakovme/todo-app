<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Application\Actions\Action;
use App\Domain\Task\TaskRepositoryInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractTaskAction extends Action
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(LoggerInterface $logger, TaskRepositoryInterface $taskRepository)
    {
        parent::__construct($logger);
        $this->taskRepository = $taskRepository;
    }
}
