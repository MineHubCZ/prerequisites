<?php

namespace MineHub\Prerequisities\AST;

use MineHub\Prerequisities\Variables;

class AndNode implements Node
{
    public function __construct(
        public readonly Node $right,
        public readonly Node $left,
    ) {

    }

    public function eval(Variables $variables): bool
    {
        return $this->left->eval($variables) && $this->right->eval($variables);
    }
}
