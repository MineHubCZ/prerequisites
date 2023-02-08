# Prerequisities

Library for evaluating prerequisites writen in simple infix language.

## Documentation

### About

This library evaluates simple c-like logical expressions with given variables. It was made for MineHub academys prerequisites.

### Syntax

As previously mentioned, it uses c-like logical expression syntax.

#### Variables

Variable names can contain every character except those: `()|&! ` - these are reserved for other syntax. Variables can be only boolean and will be loaded from eval function. See [evaluation](#evaluation)

In examples we would use true and false to make it simpler, but you can use any variable name you want, as long as it doesn't contain reserved words.

#### Operators

You can use operators or (`||`) with and (`&&`), where and has bigger priority, meaning that

```c
true || true && false
```

would evaluate as `true`, instead of `false`.

#### Groups

To change priority, you can use groups (just put it in brackets). So this

```c
(true || false ) && false
```

would evaluate as `false`

#### Negation

We can also negate variables and groups:

```c
!true
```

would evaluate as `false`, and

```c
!(true || false)
```

would also evaluate as `false`.

#### Everything is expression!

Everything is expression, so you can do stuff like:

```c
(true || (false || true)) && (!(true || (false || true)) && false)
```

#### Formal definition

TODO maybe its not correct

```
<variable> ::= [^\s\(\)\|&!]+
<expression> ::= <variable>
<expression> ::= <expression>"||"<expression>
<expression> ::= <expression>"&&"<expression>
<expression> ::= "("<expression>")"
<expression> ::= "!"<expression>
```

### Evaluation

To evaluate string, simply create instance of `\MineHub\Prerequisities\Evaluator` and use `eval` method. This method takes code and array of variables:

```php
$evaluator = new Evaluator();
$evaluator->eval('!(!foo || bar) && (foo && !bar)', ['foo' => true, 'bar' => false]); // returns true 
```


