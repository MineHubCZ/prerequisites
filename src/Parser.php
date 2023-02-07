<?php

namespace MineHub\Prerequisities;

use MineHub\Prerequisities\AST\Group;
use MineHub\Prerequisities\AST\Node;
use MineHub\Prerequisities\AST\Not;
use MineHub\Prerequisities\AST\Variable;

class Parser
{
    public function __construct(
        private Stream $stream
    ) {

    }

    public function parse(): Node
    {
        
    }

    public function parseExpression(?TokenKind $break = null): ?Node
    {
        $node = $this->parseNot()
            ?? $this->parseGroup()
        ;

        if ($node) {
            return $node;
        }

        $ors = 0;
        $result = new Stack();

        while (($this->stream->curent()?->kind ?? null) === $break) {
            switch ($this->stream->curent()->kind) {
                case TokenKind::Or:
                    $ors++;
                case TokenKind::And:

            }
        }
    }

    public function parseNot(): ?Node
    {
        if ($this->stream->curent()->kind !== TokenKind::Not) {
            return null;
        }

        $this->stream->move();
        return new Not($this->parseExpression());
    }

    public function parseGroup(): ?Node
    {
        if ($this->stream->curent()->kind !== TokenKind::Open) {
            return null;
        }

        $this->stream->move();
        return new Group($this->parseExpression(TokenKind::Close));
    }

    public function parseVariable(): ?Node
    {
        if ($this->stream->curent()->kind !== TokenKind::Variable) {
            return null;
        }

        return new Variable($this->stream->curent()->content);

    }
}
