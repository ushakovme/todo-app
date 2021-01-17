<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\CycleORM;


use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Task;
use App\Domain\Task\TaskId;
use App\Domain\Task\TaskRepositoryInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Transaction;

class SQLTaskRepository implements TaskRepositoryInterface
{
    protected Select $select;
    protected ORMInterface $orm;

    public function __construct(ORMInterface $orm)
    {
        $this->select = new Select($orm, Task::class);
        $this->orm = $orm;
    }

    public function findById(TaskId $id): Task
    {
        $task = $this->select()->wherePK($id->toInt())->fetchOne();
        if (!$task) {
            throw new TaskNotFoundException();
        }

        return $task;
    }

    public function findOne(array $scope = [])
    {
        return $this->select()->fetchOne($scope);
    }

    public function findAll(array $scope = [], array $orderBy = []): iterable
    {
        return $this->select()->where($scope)->orderBy($orderBy)->fetchAll();
    }

    public function select(): Select
    {
        return clone $this->select;
    }

    public function save(Task $task)
    {
        $transaction = new Transaction($this->orm);
        $transaction->persist(
            $task,
            Transaction::MODE_ENTITY_ONLY
        );
        $transaction->run();
    }
}
