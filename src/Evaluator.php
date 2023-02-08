<?php

namespace MineHub\Prerequisities;

use MineHub\Prerequisities\AST\Node;

class Evaluator
{
    public readonly Lexer $lexer; 

    public readonly Parser $parser; 

    public function __construct()
    {
        $this->lexer = new Lexer();
        $this->parser = new Parser();
    }

    public function eval(string $code, array $variables): bool
    {
        return $this->parse($code)
                    ->eval(new Variables($variables))
        ;
    }

    public function parse(string $code): Node
    {
        return $this->parser->parse($this->lexer->lex($code));
    }
}
