<?php

/**
 * short binding for EchoPrinter - include this file or copy the function directly into your bootstrap or like
 * it will return a basic format
 * @see EchoPrinter
 *
 * @author t
 * @version 1.2 2014-08-18
 * @license wtfpl
 */

/**
 * I bind echox() to EchoPrinter::echop to return a simple format of $param
 * @see EchoPrinter::echop
 * @return string|void
 */
function echon($param, $returnOnly=true, $indent=0, $maxDepth=9, $isHtml=false, $phpDoc=false) {
	return EchoPrinter::echop($param, $returnOnly, $indent, $maxDepth, $isHtml, $phpDoc);
}
