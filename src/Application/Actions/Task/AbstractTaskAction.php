<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Application\Actions\Action;
use App\Domain\Task\TaskRepositoryInterface;
use App\Domain\Task\TaskService;
use Psr\Log\LoggerInterface;

abstract class AbstractTaskAction extends Action
{
    protected TaskRepositoryInterface $taskRepository;
    protected TaskService $taskService;

    public function __construct(
        LoggerInterface $logger,
        TaskRepositoryInterface $taskRepository,
        TaskService $taskService
    ) {
        parent::__construct($logger);
        $this->taskRepository = $taskRepository;
        $this->taskService = $taskService;
    }
}
