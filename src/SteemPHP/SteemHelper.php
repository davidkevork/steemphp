<?php

namespace SteemPHP;

/**
 * Steem Helper Library
 */
trait SteemHelper
{
	public static function toInt($string)
	{
		return preg_replace('/[^0-9]/', '', $string);
	}

	public static function filter_int($int)
	{
		return filter_var($int, FILTER_VALIDATE_INT);
	}

	public static function reputation($rep)
	{
		if (is_null($rep) || !is_numeric($rep)) {
			return 0;
		} else {
			$e = $rep;
			$r =  (self::charAt($e, 0) === "-");
			$e = $r ? substr($e, 1) : $e;
			$a = log10($e);
			$a = max($a - 9, 0);
			$a = ($r ? -1 : 1) * $a;
			$a = ($a * 9)  + 25;
			return intval($a);
		}
	}

	public static function vestToSteem($t, $r, $e)
	{
		return floatval($r) * floatval($t) / floatval($e);
	}

	public static function charAt($string, $pos)
	{
		return $string{$pos};
	}

}



?>