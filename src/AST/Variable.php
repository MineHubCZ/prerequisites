<?php

namespace MineHub\Prerequisities\AST;

use MineHub\Prerequisities\Variables;
use ParseError;

class Variable
{
    public function __construct(
        public readonly string $name,
    ) {

    }

    public function eval(Variables $variables): bool
    {
        if (!$variables->has($this->name)) {
            throw new ParseError('Undefined variable '.$this->name);
        }
        return $variables->get($this->name);
    }
}
