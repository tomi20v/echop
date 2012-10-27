echop
=====

pretty printer for php, like print_r or var_dump. echop prints more details than print_r but is more compact than var_dump. Also prints constants and static vars for objects.

I normally include it in php.ini by auto_prepend_file directive, but can be included anytime, anywhere.

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
	     