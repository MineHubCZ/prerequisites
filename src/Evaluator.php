<?php

namespace MineHub\Prerequisities;

class Evaluator
{
    public static function eval(string $code, array $variables): bool
    {
        $lexer = new Lexer();
        $parser = new Parser($lexer->lex($code));
        return $parser->parse()->eval(new Variables($variables));
    }
}
