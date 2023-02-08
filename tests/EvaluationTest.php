<?php

use MineHub\Prerequisities\AST\AndNode;
use MineHub\Prerequisities\AST\Not;
use MineHub\Prerequisities\AST\OrNode;
use MineHub\Prerequisities\AST\Variable;
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


