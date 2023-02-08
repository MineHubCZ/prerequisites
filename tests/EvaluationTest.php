<?php

use MineHub\Prerequisities\AST\AndNode;
use MineHub\Prerequisities\AST\Not;
use MineHub\Prerequisities\AST\OrNode;
use MineHub\Prerequisities\AST\Variable;
use MineHub\Prerequisities\Evaluator;
use MineHub\Prerequisities\Variables;

$variables = new Variables(['foo' => true, 'bar' => false]);

it('evaluates variable')
    ->expect((new Variable('foo'))->eval($variables))
    ->toBeTrue()
;

it('wont evaluate not-existing variable', 
    fn() => (new Variable('parek'))->eval($variables)
)
    ->throws(ParseError::class)
;

it('negates variable')
    ->expect((new Not(new Variable('foo')))->eval($variables))
    ->toBeFalse()
;

it('evaluates or')
    ->expect((new OrNode(new Variable('foo'), new Variable('bar')))->eval($variables))
    ->toBeTrue()
;

it('evaluates and')
    ->expect((new AndNode(new Variable('foo'), new Variable('bar')))->eval($variables))
    ->toBeFalse()
;

it('evaluates complex group')
    ->expect((new OrNode(
        new Variable('bar'),
        new OrNode(new AndNode(new Variable('bar'), new Variable('foo')), new Variable('foo')),
    ))->eval($variables))
    ->toBeTrue()
;

$evaluator = new Evaluator();
it('evaluates complex string')
    ->expect($evaluator->eval('!(!foo || bar) && (foo && !bar)', ['foo' => true, 'bar' => false]))
    ->toBeTrue()
;

it('evaluates crazy string')
    ->expect($evaluator->eval('(true || (false || true)) && (!(true || (false || true)) && false)', ['true' => true, 'false' => false]))
    ->toBeFalse()
;

it('evaluates nonsence')
    ->expect($evaluator->eval('(((((true)))))', ['true' => true]))
    ->toBeTrue()
;
