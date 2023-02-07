<?php

use MineHub\Prerequisities\AST\AndNode;
use MineHub\Prerequisities\AST\OrNode;
use MineHub\Prerequisities\AST\Variable;
use MineHub\Prerequisities\Parser;
use MineHub\Prerequisities\Stream;
use MineHub\Prerequisities\Token;
use MineHub\Prerequisities\TokenKind;

it('parses just variable', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Variable, 'foo')
    ]));
    expect($parser->parse())->toEqual(new Variable('foo'));
});

it('parses simple infix expression', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'bar')
    ]));
    expect($parser->parse())->toEqual(new OrNode(new Variable('bar'), new Variable('foo')));
});

it('parses more complex infix expression', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::And, '&&'),
        new Token(TokenKind::Variable, 'bar'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'baz'),
    ]));

    expect($parser->parse())->toEqual(new OrNode(new Variable('baz'), new AndNode(new Variable('bar'), new Variable('foo'))));
});
