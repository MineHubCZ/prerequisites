<?php

namespace MineHub\Prerequisities;

use ParseError;

class Lexer
{
    public const Tokens = [
        ['||', 'Or'],
        ['&&', 'And'],
        ['!', 'Not'],
        ['(', 'Open'],
        [')', 'Close'],
        ['\S+', 'Variable'],
        ['\s+', 'Space'],
        ['.+', 'Error'],
    ];

    public function lex(string $code): Stream
    {
        $re = '~'.implode('|', array_map(fn($item) => '(<'.$item[1].'>'.$item[0].')', self::Tokens)).'~';
        preg_match_all($re, $code, $matches, PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER);

        return new Stream(array_filter(array_map(function($token) {
            $token = array_filter($token, fn ($item) => null !== $item);
            $keys = array_keys($token);

            $kind = $keys[1];

            if ($kind === 'Space') {
                return null;
            }

            if ($kind === 'Error') {
                throw new ParseError('Bad input');
            }

            $result = new Token(TokenKind::$kind, $token[0]); 

            return $result;
        }, $matches)));
    }
}
