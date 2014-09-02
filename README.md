EchoPrinter / echop
=====

Pretty all-purpose printer for PHP. More condensed and informative format 
than print_r or var_dump. Also prints accessible static properties, and 
constants. More detailed html format or slim console format, with or 
without phpdoc inprint

It is somewhat composer compatible, yet to be added to packagist for code heaven.

function echop($var, $returnOnly) is a shorthand for calling EchoPrinter::echop()

/**
 * pretty printer
 * 	I will print all kinds of data in a condensed but nice format
 * 	I will print constants and static properties of objects as well
 * 	I will detect cycle references in objects (but not in arrays)
 * 	I will stop at a certain depth level so array recursion is caught as well 	
 * @param mixed $param I will print this nicely
 * @param boolean $returnOnly if true, I only return in a string, otherwise I print
 * @param int $indent what I print will be indented this much
 * @param int $maxDepth print no deeper than this but print *DEPTH LIMIT* instead. 0 means no limit
 * @param boolean $isHtml if true I'll do some HTML formatting (&lt;pre&gt; and &lt;b&gt; tags )
 * @return string|void
 * @see https://github.com/tomi20v/echop
 */

changelog

1.1 2012-11-11
echop() moved inside EchoPrinter class to encapsulate it and its utilities
can look up and print PhpDoc comments on vars

1.0 2012-10-17
initial version
