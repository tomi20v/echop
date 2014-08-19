<?php

/**
 * short binding for EchoPrinter - include this file or copy the function directly into your bootstrap or like
 * it will print with all features
 * @see EchoPrinter
 *
 * @author t
 * @version 1.2 2014-08-18
 * @license wtfpl
 */

/**
 * I bind echox() to EchoPrinter::echop using all features
 * @see EchoPrinter::echop
 * @return string|void
 */
function echox($param, $returnOnly=false, $indent=0, $maxDepth=9, $isHtml=true, $phpDoc=true) {
	return EchoPrinter::echop($param, $returnOnly, $indent, $maxDepth, $isHtml, $phpDoc);
}
