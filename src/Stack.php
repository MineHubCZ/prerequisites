<?php

declare(strict_types=1);

namespace MineHub\Prerequisities;

use Throwable;

class Stack
{
    private array $data = [];

    public function push(mixed $value): self
    {
        $this->data[] = $value;

        return $this;
    }

    public function pop(?Throwable $error = null): mixed
    {
        return array_pop($this->data) ?? ($error ? throw $error : null);
    }

    public function top(): mixed
    {
        return $this->data[count($this->data) - 1] ?? null;
    }
}

