<?php

use MineHub\Prerequisities\AST\AndNode;
use MineHub\Prerequisities\AST\Not;
use MineHub\Prerequisities\AST\OrNode;
use MineHub\Prerequisities\AST\Variable;
use MineHub\Prerequisities\Parser;
use MineHub\Prerequisities\Stream;
use MineHub\Prerequisities\Token;
use MineHub\Prerequisities\TokenKind;

$parser = new Parser();

it('parses just variable', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Variable, 'foo')
    ]);
    expect($parser->parse($tokens))->toEqual(new Variable('foo'));
});

it('parses simple infix expression', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'bar')
    ]);
    expect($parser->parse($tokens))->toEqual(new OrNode(new Variable('bar'), new Variable('foo')));
});

it('parses more complex infix expression', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::And, '&&'),
        new Token(TokenKind::Variable, 'bar'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'baz'),
    ]);

    expect($parser->parse($tokens))
        ->toEqual(new OrNode(new Variable('baz'), new AndNode(new Variable('bar'), new Variable('foo'))));
});

it('parses simple groups', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'klobas'),
        new Token(TokenKind::Close, ')'),
    ]);

    expect($parser->parse($tokens))
        ->toEqual(new Variable('klobas'))
    ;
});

it('parses groups with expressions', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'bar'),
        new Token(TokenKind::Close, ')'),
    ]);  

    expect($parser->parse($tokens))
        ->toEqual(
            new OrNode(new Variable('bar'), new Variable('foo'))
        )
    ;
});

it('parses complex groups', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'bar'),
        new Token(TokenKind::Close, ')'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::And, '&&'),
        new Token(TokenKind::Variable, 'bar'),
        new Token(TokenKind::Close, ')'),
    ]);  

    expect($parser->parse($tokens))
        ->toEqual(new OrNode(
            new AndNode(new Variable('bar'), new Variable('foo')),
            new OrNode(new Variable('bar'), new Variable('foo'))
        ))
    ;
});

it('parses nested groups', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::And, '&&'),
        new Token(TokenKind::Variable, 'bar'),
        new Token(TokenKind::Close, ')'),
        new Token(TokenKind::Close, ')'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'bar'),
    ]);  

    expect($parser->parse($tokens))
        ->toEqual(new OrNode(
            new Variable('bar'),
            new OrNode(new AndNode(new Variable('bar'), new Variable('foo')), new Variable('foo')),
        ))
    ;
});

it('parses not', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Not, '!'),
        new Token(TokenKind::Variable, 'chlebak'),
    ]);

    expect($parser->parse($tokens))
        ->toEqual(new Not(new Variable('chlebak')))
    ;
});

it('parses not with operators', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Not, '!'),
        new Token(TokenKind::Variable, 'chlebak'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'rizkoparek')
    ]);

    expect($parser->parse($tokens))
        ->toEqual(new OrNode(new Variable('rizkoparek'), new Not(new Variable('chlebak'))))
    ;
});

it('parses not on groups', function() use ($parser) {
    $tokens = new Stream([
        new Token(TokenKind::Not, '!'),
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'chlebak'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'rizkoparek'),
        new Token(TokenKind::Close, ')'),
    ]);

    expect($parser->parse($tokens))
        ->toEqual(new Not(new OrNode(new Variable('rizkoparek'), new Variable('chlebak'))))
    ;
});
