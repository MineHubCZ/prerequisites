<?php

namespace MineHub\Prerequisities;

use MineHub\Prerequisities\AST\AndNode;
use MineHub\Prerequisities\AST\Node;
use MineHub\Prerequisities\AST\Not;
use MineHub\Prerequisities\AST\OrNode;
use MineHub\Prerequisities\AST\Variable;
use ParseError;

class Parser
{
    public function parse(Stream $stream): Node
    {
        $operators = new Stack();
        $result = new Stack();
        while ($token = $stream->curent()) {
            switch ($token->kind) {
                case TokenKind::Variable:
                    $node = new Variable($token->content);
                    if ($operators->top() === TokenKind::Not) {
                        $operators->pop(new ParseError());
                        $node = new Not($node);
                    }
                    $result->push($node);
                    break;
                case TokenKind::Or:
                case TokenKind::And:
                    if ($operators->top() === TokenKind::And) {
                        $operators->pop();
                        $result->push(new AndNode($result->pop(new ParseError('Unexpected token')), $result->pop(new ParseError('Unexpected token'))));
                    }
                    $operators->push($token->kind);
                    break;
                case TokenKind::Open:
                    $operators->push(TokenKind::Open);
                    break;
                case TokenKind::Close:
                    while (($op = $operators->pop(new ParseError('Unexpected )'))) !== TokenKind::Open) {
                        $result->push(new ($op->toNode())($result->pop(new ParseError('Unexpected token')), $result->pop(new ParseError('Unexpected token'))));                  
                    }

                    if ($operators->top() === TokenKind::Not) {
                        $result->push(new Not($result->pop()));
                        $operators->pop();
                    }
                    break;
                case TokenKind::Not:
                    $operators->push(TokenKind::Not);
                    break;
            }
            $stream->move();
        }   

        while (($op = $operators->pop())) {
            $result->push(new ($op->toNode())($result->pop(new ParseError('Unexpected token')), $result->pop(new ParseError('Unexpected token'))));                  
        }

        return $result->pop();
    }
}
