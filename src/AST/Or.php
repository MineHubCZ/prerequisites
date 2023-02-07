<?php

namespace MineHub\Prerequisities\AST;

use MineHub\Prerequisities\Variables;

class Or implements Node
{
    public function __construct(
        public readonly Node $left,
        public readonly Node $right,
    ) {

    }

    public function eval(Variables $variables): bool
    {
        return $this->left->eval($variables) || $this->right->eval($variables);
    }
}
