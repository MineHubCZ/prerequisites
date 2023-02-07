<?php

namespace MineHub\Prerequisities;

use MineHub\Prerequisities\AST\Node;

class Parser
{
    public function __construct(
        private Stream $stream
    ) {

    }

    public function parse(): Node
    {
        
    }

    public function parseExpression(): ?Node
    {

    }

    public function parseNot(): ?Node
    {

    }

    public function parseGroup(): ?Node
    {

    }

    public function parseVariable(): ?Node
    {

    }
}
