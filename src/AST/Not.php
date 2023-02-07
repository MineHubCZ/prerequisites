<?php

namespace MineHub\Prerequisities\AST;

use MineHub\Prerequisities\Variables;

class Not implements Node
{
    public function __construct(
        public readonly Node $node,
    ) {

    }

    public function eval(Variables $variables): bool
    {
        return !$this->node->eval($variables);
    }
}
