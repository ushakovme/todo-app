<?php
declare(strict_types=1);

namespace App\Domain\Task;

final class TaskId
{
    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromInt(int $id): TaskId
    {
        return new TaskId($id);
    }

    public function toInt(): int
    {
        return $this->id;
    }

    public function toString(): string
    {
        return (string)$this->id;
    }
}
