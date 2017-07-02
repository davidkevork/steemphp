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

	public static function filterInt($int)
	{
		return filter_var($int, FILTER_VALIDATE_INT);
	}

	public static function filterDate($date)
	{
		$date = strtotime($date) ? $date : date('Y-m-d H:i:s', $date);
		$dt = new \DateTime($date);
		$dt->setTimeZone(new \DateTimeZone('UTC'));
		return $dt->format('Y-m-d\TH-i-s');
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

	public static function estimateAccountValue($data, $market, $account)
	{
		if (is_null($data) && !is_array($data)) {
			return 0;
		} else {

        	$props = $data['props'];
        	$feed_price = $data['feed_price'];
        	$open_orders = $market['open_orders'];
        	$vesting_steem = $data['accounts'][$account]['reward_vesting_steem'];
        	$assetPrecision = 1000;
        	$orders = 0;
        	$savings = 0;

        	if (empty($vesting_steem) || !is_array($feed_price)) {

        	}

			$vesting_balance = $data['accounts'][$account]['vesting_balance'];
			$c = $data['accounts'][$account]['savings_withdraw_requests'];
			$h = $data['props']['pending_rewarded_vesting_steem'];
			$o = $data['accounts'][$account]['savings_balance'];
			$s = $data['accounts'][$account]['savings_sbd_balance'];
			$f = floatval($data['accounts'][$account]['balance']);
			$c = floatval(explode(' ', $o)[0]);
			$l = floatval($data['accounts'][$account]['sbd_balance']);
			$p = floatval(explode(' ', $s)[0]);
			/*s = $data['props'];
			$u = $data['feed_price'];
			$f = 1.633440486125; // $market['open_orders']
			$c = $data['accounts'][$account]['savings_withdraw_requests'];
			$h = $data['props']['pending_rewarded_vesting_steem'];
			$l = [];
			$p = $data['accounts'][$account]['name'];
			$d = 1000;
			$v = 0;
			$g = $c;
			$t = 0;
			$r = $u;
			$n = $u['base'];
			$i = $u['quote'];
			if (self::contains($n, 'SBD') && self::contains($i, 'STEEM'))
			{
				$t = explode(' ', $n)[0];
			}
			$o = $data['accounts'][$account]['savings_balance'];
			$s = $data['accounts'][$account]['savings_sbd_balance'];
			$f = floatval($data['accounts'][$account]['balance']);
			$c = floatval(explode(' ', $o)[0]);
			$l = floatval($data['accounts'][$account]['sbd_balance']);
			$p = floatval(explode(' ', $s)[0]);
			$d = 0;
			$y = time();
			$other = !empty($data['accounts'][$account]['other_history']) ? $data['accounts'][$account]['other_history'] : [];
			foreach ($other as $key => $value) {
				if ($value[1]['op'][0] != 'convert') { return $t; }
				$r = date($value[1]['timestamp']);
				$n = $r + 3024**5;
				if ($n < $y) { return $t; }
				$i = floatval($value[1]['op'][1]['amount']);
				$d += $i;
			}
			$value = $l + $p + $d;
			// self::toInt($h)
			$m = $f + $c;
			return number_format($value * ($m + $t));*/
		}
	}

	public static function vestToSteem($t, $r, $e)
	{
		return floatval($r) * floatval($t) / floatval($e);
	}

	public static function contains($data, $contains)
	{
		return preg_match('/('.$contains.')/', $data);
	}

	public static function charAt($string, $pos)
	{
		return $string{$pos};
	}

}



?>