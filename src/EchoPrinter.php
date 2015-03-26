<?php

/**
 * Pretty all-purpose printer for PHP. More condensed and informative format
 *		than print_r or var_dump. Also prints accessible static properties, and
 *		constants. More detailed html format or slim console format, with or
 *		without phpdoc inprint
 * @author t
 * @version 1.3.2 2015-03-26
 * @license wtfpl
 */
class EchoPrinter {

	/**
	 * I try to fetch phpdoc doc of a property recursively
	 * @param string $className
	 * @param string $propertyName
	 * @return string
	 */
	public static function getPropertyDoc($className, $propertyName) {
		$classNames = array();
		do {
			$classNames[] = $className;
		} while ($className = get_parent_class($className));
		$doc = '';
		foreach ($classNames AS $eachClassName) {
			try {
				$p = new ReflectionProperty($eachClassName, $propertyName);
			}
			catch (\Exception $e) { continue ; }
			$doc = $p->getDocComment();
			if ($doc != '') {
				break;
			}
		}
		return $doc;
	}

	/**
	 * I format the phpdocs a bit to get a more condensed format, stripping block indenting too
	 * @param unknown $doc
	 * @return mixed
	 */
	protected static function _formatPropertyDoc($doc) {
		if ($doc !== '') {
			$doc = str_replace("\r", '', $doc);
			$doc = preg_replace('/(\n[\t]+)/', "\n", $doc);
			$doc = str_replace("*\n *", "*", $doc);
			if (count(explode("\n", $doc)) <= 2) {
				$doc = str_replace("\n */", " */", $doc);
			}
		}
		return $doc;
	}

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
	public static function echop($param, $returnOnly=false, $indent=0, $maxDepth=9, $isHtml=null, $phpDoc=null) {

		static $references = array();
		$str = '';

		$phpDoc = is_null($phpDoc)
				? (is_null($isHtml) ? !empty($_SERVER['HTTP_HOST']) : ($isHtml?true:false))
				: ($phpDoc ? true : false);
		$isHtml = is_null($isHtml)
				? !empty($_SERVER['HTTP_HOST'])
				: ($isHtml?true:false);

		// if null...
		if (is_null($param)) {
			$str = $isHtml ? '<b>NULL</b>' : 'NULL';
		}
		// print objects
		elseif (is_object($param)) {

			$hash = spl_object_hash($param);

			// check circular reference
			if (in_array($hash, $references, true)) {
				$str = $isHtml ? '<b>*RECURSION*</b>' : '*RECURSION*';
			}
			else {
				// push onto reference stack
				$references[] = $hash;

				$className = get_class($param);
				$parentClassName = get_parent_class($param);
				$r = new \ReflectionClass($className);

				// classname
				$str = $className . (empty($parentClassName) ? ' Object' : ' extends ' . $parentClassName) . ' (';

				// depth limit check, by indent
				if ($maxDepth && ($maxDepth <= $indent)) {
					$str.= $isHtml ? '<b>*DEPTH LIMIT*</b>)' : '*DEPTH LIMIT*)';
				}
				else {

					// constants
					foreach ($r->getConstants() AS $eachConstantName=>$eachConstant) {
						$str.= "\n" . str_repeat("\t", $indent+1) .
								'const [' . ($isHtml ? '<b>' . $eachConstantName . '</b>' : $eachConstantName) . ']' .
								' => ' .
								($isHtml ? '<b>' . $eachConstant . '</b>' : $eachConstant);
					}

					// get an array of static & dynamic property names, statics first
					$staticPNames = array_keys($r->getStaticProperties());
					$allPNames = array_map(function($property) { return $property->name; }, $r->getProperties());
					$propertyNames = array_merge($staticPNames, array_diff($allPNames, $staticPNames));

					// print them
					foreach ($propertyNames AS $eachPropertyName) {
						$p = new ReflectionProperty($className, $eachPropertyName);
						if ($phpDoc) {
							$doc = static::_formatPropertyDoc(static::getPropertyDoc($className, $eachPropertyName));
						}
						$m = $p->getModifiers();
						$visiblity =
							($m & ReflectionProperty::IS_PRIVATE ? 'private' : '') .
							($m & ReflectionProperty::IS_PROTECTED ? 'protected' : '') .
							($m & ReflectionProperty::IS_PUBLIC ? 'public' : '');
						$isStatic = $m & ReflectionProperty::IS_STATIC ? true : false;
						$p->setAccessible(true);

						$val = $isStatic ? $p->getValue() : $p->getValue($param);

						$str.= ($phpDoc ? str_replace("\n", "\n" . str_repeat("\t", $indent+1), "\n" . $doc) : '') .
								"\n" . str_repeat("\t", $indent+1) .
								($isStatic ? 'static ' : '') .
									'[' . ($isHtml ? '<b>' . $eachPropertyName . '</b>' : $eachPropertyName) . ':' . $visiblity . ']' .
								" => " .
								static::echop($val, true, $indent+1, $maxDepth, $isHtml, $phpDoc);
					}
					$str.= "\n" . str_repeat("\t", $indent) . ')';
				}

				// pop from reference stack
				array_pop($references);
			}
		}
		// print arrays
		elseif (is_array($param)) {

			// empty arrays should look really compact
			if (empty($param)) {
				$str.= 'Array()';
			}
			else {
				$str.= 'Array(' . count($param) . ') (';

				// check max depth
				if ($maxDepth && ($maxDepth<=$indent)) {
					$str.= ($isHtml ? '<b>*DEPTH LIMIT*</b>' : '*DEPTH LIMIT*' ) . ')';
				}
				else {
					foreach ($param AS $eachKey=>$eachValue) {
						$str.= "\n" . str_repeat("\t", $indent+1) . '[' . $eachKey . "] => " . static::echop($eachValue, true, $indent+1, $maxDepth, $isHtml, $phpDoc);
					}
					$str.= "\n" . str_repeat("\t", $indent) . ')';
				}
			}
		}
		// print string, int, other scalars
		elseif (is_string($param) || is_numeric($param)) {
			$str = 'string(' . strlen($param) . ') ' . ($isHtml ? '<b>' . $param . '</b>' : $param);
		}
		// print bool
		elseif (is_bool($param)) {
			$str = 'bool(' . ($param ? ($isHtml ? '<b>true</b>' : 'TRUE') : ($isHtml ? '<b>false</b>' : 'FALSE')) . ')';
		}
		// we get here for resources (or whatever else left), simply print with print_r
		else {
			$str = print_r($param, 1);
		}

		// put in <pre> tags in html mode
		if ($isHtml && !$indent) {
			$str = "\n" . '<pre>' . $str . '</pre>' . "\n";
		}

		// return or print
		if ($returnOnly) {
			return $str;
		}
		echo $str . "\n";

	}

}
