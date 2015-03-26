<?php

/**
 * short binding for EchoPrinter - include this file or copy the function directly into your bootstrap or like
 * it will print without phpdoc
 * @see EchoPrinter
 *
 * @author t
 * @version 1.2 2014-08-18
 * @license wtfpl
 */

/**
 * I bind echop() to EchoPrinter::echop without phpdoc printing
 * @see EchoPrinter::echop()
 * @return string|void
 */
function echop($param, $returnOnly=false, $indent=0, $maxDepth=9, $isHtml=true, $phpDoc=false) {
	return EchoPrinter::echop($param, $returnOnly, $indent, $maxDepth, $isHtml, $phpDoc);
}
