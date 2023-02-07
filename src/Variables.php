<?php

namespace MineHub\Prerequisities;

class Variables
{
    public readonly array $variables;

    public function __construct(bool ...$variables)
    {
        $this->variables = $variables;
    }

    public function get(string $name): bool
    {
        return $this->variables[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->variables[$name]);
    }
}
