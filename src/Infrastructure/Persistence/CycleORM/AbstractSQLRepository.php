<?php

namespace App\Infrastructure\Persistence\CycleORM\Task;

use App\Domain\AbstractId;
use Cycle\ORM\Select;

class AbstractSQLRepository
{
    protected Select $select;

    public function __construct(Select $select)
    {
        $this->select = $select;
    }

    public function findById(AbstractId $id)
    {
        return $this->select()->wherePK($id->toInt())->fetchOne();
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
}
