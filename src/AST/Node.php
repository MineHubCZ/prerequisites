<?php

namespace MineHub\Prerequisities\AST;

use MineHub\Prerequisities\Variables;

interface Node
{
    public function eval(Variables $variables): bool;
}
