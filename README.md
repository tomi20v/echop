# EchoPrinter / echop
=====

## Pretty all-purpose printer for PHP.

More condensed and informative format than print_r or var_dump. Also prints
accessible static properties, and constants. More detailed html format or slim
console format, with or without phpdoc inprint

There are some bindings in separate files to include so you can have the
simplest printer commands

## from the source:

```
/**
 * the pretty printer
 * 	I will print all kinds of data in a condensed but nice format
 * 	I will print constants and static properties of objects as well
 * 	I will detect cycle references in objects (but not in arrays)
 * 	I will stop at a certain depth level so array recursion is caught as well
 * @param mixed $param I will print this nicely
 * @param boolean $returnOnly if true, I only return in a string, otherwise I print
 * @param int $indent what I print will be indented this much
 * @param int $maxDepth print no deeper than this but print *DEPTH LIMIT* instead. 0 means no limit
 * @param boolean $isHtml if true I'll do some HTML formatting (<pre> and <b> tags )
 * @param boolean $phpDoc if true, it will look for phpdoc tags of all properties etc
 * @return string|void
 */
```

## an example output with all extras:

    MyClass Object (
        const [**MAP_C**] => 2
        /** @var string this is the first variable */
        static [**pus**:public] => string(3) **pus**
        /** @var string this is the second variable */
        static [**_prs**:protected] => string(3) **prs**
        /** @var string this is my protected property */
        [**_pro**:protected] => string(3) **pro**
        /** @var string this is another protected property */
        [**_pro2**:protected] => string(3) **pro2**
        /** @var string and finally public */
        [**pu**:public] => string(2) **pu**
    )

## changelog

1.3.2 2015-03-26
fix composer.json, some prettify, bump

1.3.1 2014-09-05
added type library to composer.json

1.3 2014-09-05
made packagist friendly
added new binding "echon" which always returns printout

1.2 2014-08-20
added license
fixed: not passing $phpDoc parameter recursively
binding functions are now in separate files

1.1 2012-11-11
echop() moved inside EchoPrinter class to encapsulate it and its utilities
can look up and print PhpDoc comments on vars

1.0 2012-10-17
initial version

## why?

my first echop() implementation only wrapped print_r, with optional second
parameter value of 2, making script to dump and die. I've got addicted as
I could write the word 'echop' much faster than print_r or var_dump...
this was 15 years ago. since then it evolved a bit and lately also served
some experiments like 'put something on composer'

