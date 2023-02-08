<?php

namespace MineHub\Prerequisities;

use MineHub\Prerequisities\AST\AndNode;
use MineHub\Prerequisities\AST\Not;
use MineHub\Prerequisities\AST\OrNode;
use MineHub\Prerequisities\AST\Variable;

enum TokenKind
{
    case Or;
    case And;
    case Not;
    case Open;
    case Close;
    case Variable;

    public static function tryFrom(string $kind): self
    {
        return match($kind) {
            'Or' => self::Or,
            'And' => self::And,
            'Not' => self::Not,
            'Open' => self::Open,
            'Close' => self::Close,
            'Variable' => self::Variable,
        };
    }

    public function toNode(): string
    {
        return match($this) {
            TokenKind::Or => OrNode::class,
            TokenKind::And => AndNode::class,
            TokenKind::Not => Not::class,
            TokenKind::Variable => Variable::class,
            default => throw new \Exception('If you can read this, then something bad happened')
        };
    }
}
