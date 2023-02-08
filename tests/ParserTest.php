<?php

use MineHub\Prerequisities\AST\AndNode;
use MineHub\Prerequisities\AST\Not;
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

    expect($parser->parse())
        ->toEqual(new OrNode(new Variable('baz'), new AndNode(new Variable('bar'), new Variable('foo'))));
});

it('parses simple groups', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'klobas'),
        new Token(TokenKind::Close, ')'),
    ]));

    expect($parser->parse())
        ->toEqual(new Variable('klobas'))
    ;
});

it('parses groups with expressions', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'foo'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'bar'),
        new Token(TokenKind::Close, ')'),
    ]));  

    expect($parser->parse())
        ->toEqual(
            new OrNode(new Variable('bar'), new Variable('foo'))
        )
    ;
});

it('parses complex groups', function() {
    $parser = new Parser(new Stream([
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
    ]));  

    expect($parser->parse())
        ->toEqual(new OrNode(
            new AndNode(new Variable('bar'), new Variable('foo')),
            new OrNode(new Variable('bar'), new Variable('foo'))
        ))
    ;
});

it('parses nested groups', function() {
    $parser = new Parser(new Stream([
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
    ]));  

    expect($parser->parse())
        ->toEqual(new OrNode(
            new Variable('bar'),
            new OrNode(new AndNode(new Variable('bar'), new Variable('foo')), new Variable('foo')),
        ))
    ;
});

it('parses not', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Not, '!'),
        new Token(TokenKind::Variable, 'chlebak'),
    ]));

    expect($parser->parse())
        ->toEqual(new Not(new Variable('chlebak')))
    ;
});

it('parses not with operators', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Not, '!'),
        new Token(TokenKind::Variable, 'chlebak'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'rizkoparek')
    ]));

    expect($parser->parse())
        ->toEqual(new OrNode(new Variable('rizkoparek'), new Not(new Variable('chlebak'))))
    ;
});

it('parses not on groups', function() {
    $parser = new Parser(new Stream([
        new Token(TokenKind::Not, '!'),
        new Token(TokenKind::Open, '('),
        new Token(TokenKind::Variable, 'chlebak'),
        new Token(TokenKind::Or, '||'),
        new Token(TokenKind::Variable, 'rizkoparek'),
        new Token(TokenKind::Close, ')'),
    ]));

    expect($parser->parse())
        ->toEqual(new Not(new OrNode(new Variable('rizkoparek'), new Variable('chlebak'))))
    ;
});
