<?php

namespace MineHub\Prerequisities;

class Stream
{
    private int $position = 0;

    public function __construct(
        /** @var array<Token> $tokens */
        public readonly array $tokens,
    ) {

    }

    public function curent(): ?Token
    {
        return $this->tokens[$this->position] ?? null;
    }

    public function move(int $step = 1): ?Token
    {
        $this->position += $step;
        return $this->curent();
    } 

    public function peek(int $step = 1): ?Token
    {
        return $this->tokens[$this->position + $step] ?? null;
    }
}

