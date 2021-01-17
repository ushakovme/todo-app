<?php
declare(strict_types=1);

namespace App\Domain\Task;

use App\Domain\Task\Exception\TaskContentLengthException;
use JsonSerializable;

final class Task implements JsonSerializable
{
    private ?TaskId $id;
    private string $content;
    private bool $isCompleted = false;

    const MAX_TASK_LENGTH = 1000;

    public function __construct(?TaskId $id, string $content)
    {
        $this->id = $id;
        $this->setContent($content);
    }

    public function getId(): TaskId
    {
        return $this->id;
    }

    /**
     * @throws TaskContentLengthException
     */
    public function setContent(string $content)
    {
        if (strlen($content) > static::MAX_TASK_LENGTH) {
            throw new TaskContentLengthException();
        }

        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function complete()
    {
        $this->isCompleted = true;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id->toInt(),
            'content' => $this->content
        ];
    }
}
