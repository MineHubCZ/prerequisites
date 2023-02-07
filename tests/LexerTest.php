<?php

use MineHub\Prerequisities\Lexer;
use MineHub\Prerequisities\Token;
use MineHub\Prerequisities\TokenKind;

it('lexes code', function() {
    $lexer = new Lexer();
    $stream = $lexer->lex('|| && () foo BAR_13 !');
    expect($stream->tokens)
        ->toMatchArray([
            new Token(TokenKind::Or, '||'),
            new Token(TokenKind::And, '&&'),
            new Token(TokenKind::Open, '('),
            new Token(TokenKind::Close, ')'),
            new Token(TokenKind::Variable, 'foo'),
            new Token(TokenKind::Variable, 'BAR_13'),
            new Token(TokenKind::Not, '!'),
        ])
        ;

    $stream = $lexer->lex('(variable || b) && !(a && c)');
    expect($stream->tokens)
        ->toMatchArray([
            new Token(TokenKind::Open, '('),
            new Token(TokenKind::Variable, 'variable'),
            new Token(TokenKind::Or, '||'),
            new Token(TokenKind::Variable, 'b'),
            new Token(TokenKind::Close, ')'),
            new Token(TokenKind::And, '&&'),
            new Token(TokenKind::Not, '!'),
            new Token(TokenKind::Open, '('),
            new Token(TokenKind::Variable, 'a'),
            new Token(TokenKind::And, '&&'),
            new Token(TokenKind::Variable, 'c'),
            new Token(TokenKind::Close, ')'),
        ])
    ;
});
