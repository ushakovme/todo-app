<?php
declare(strict_types=1);

namespace App\Domain;

use JsonSerializable;

abstract class AbstractId implements JsonSerializable
{
    private int $id;

    protected function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromInt(int $id): self
    {
        return new static($id);
    }

    public function toInt(): int
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return $this->toInt();
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
