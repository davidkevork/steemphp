<?php

namespace SteemPHP;

/**
 * Steem Helper Library
 */
trait SteemHelper
{

	/**
	 * turns 1.90 STEEM to 1.90 by removing non-numeric characters
	 * @param String $string 
	 * @return int
	 */	
	public static function toInt($string)
	{
		return preg_replace('/[^0-9\.]/', '', $string);
	}

	/**
	 * check if an input is int or no
	 * @param int $int 
	 * @return boolean
	 */
	public static function filterInt($int)
	{
		return filter_var($int, FILTER_VALIDATE_INT);
	}

	/**
	 * transfers timestamp or normal date to the format('Y-m-d\TH-i-s')
	 * @param date/timestamp $date 
	 * @return date
	 */
	public static function filterDate($date)
	{
		$date = strtotime($date) ? $date : date('Y-m-d H:i:s', $date);
		$dt = new \DateTime($date);
		$dt->setTimeZone(new \DateTimeZone('UTC'));
		return $dt->format('Y-m-d\TH-i-s');
	}

	/**
	 * returns the reputation from a set of number
	 * @param int $rep 
	 * @return int
	 */
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

	/**
	 * get estimated account value
	 * 
	 * this code is broken and only calculated the amount of money the user currently has
	 * do not use until we fix it
	 * 
	 * @param array $data 
	 * @param array $market 
	 * @param array $account 
	 * @return int
	 */
	public static function estimateAccountValue($data, $market, $account)
	{
		if (is_null($data) && !is_array($data)) {
			return 0;
		} else {
			$props = $data['props'];
			$feed_price = $data['feed_price'];
			$savings_withdraws = $data['accounts'][$account]['savings_withdraw_requests'];
			$vesting_steem = $data['accounts'][$account]['reward_vesting_steem'];
			$assetPrecision = 1000;
			$open_orders = self::processOrders($market, $assetPrecision);
			$savings = self::calculateSaving($savings_withdraws);
			$price_per_steem = 0;
			$_feed_price = $feed_price;
			$base = $_feed_price['base'];
			$quote = $_feed_price['quote'];
			if (self::contains($base, "SBD") && self::contains($quote, "STEEM")) {
				$price_per_steem = floatval(explode(' ', $base)[0]);
			}
			$savings_balance = $data['accounts'][$account]['savings_balance'];
			$savings_sbd_balance = $data['accounts'][$account]['savings_sbd_balance'];
			$balance_steem = floatval(explode(' ', $data['accounts'][$account]['balance'])[0]);
			$saving_balance_steem = floatval(explode(' ', $savings_balance)[0]);
			$sbd_balance = floatval(explode(' ', $data['accounts'][$account]['sbd_balance'])[0]);
			$sbd_balance_savings = floatval(explode(' ', $data['accounts'][$account]['savings_sbd_balance'])[0]);
			$conversionValue = 0;
			$currentTime = time();
			foreach ($data['accounts'][$account]['other_history'] as $other_history_key => $other_history_value) {
				if ($other_history_value[1]["op"][0] == "convert") {
					$timestamp = strtotime($other_history_value[1]['timestamp']);
					$finishTime = $timestamp + 86400000 * 3.5; // 3.5 day convesion delay
					if ($finishTime > $currentTime) {
						$conversionValue += floatval(self::toInt($other_history_value[1]['op'][1]['amount']));
					}
				}
			}
			$total_sbd = self::toInt($sbd_balance) + self::toInt($sbd_balance_savings) + self::toInt($savings['savings_sbd_pending']) + self::toInt($open_orders['sbdOrders']) + self::toInt($conversionValue);
			$total_steem = self::toInt($vesting_steem) + self::toInt($balance_steem) + self::toInt($saving_balance_steem) + self::toInt($savings['savings_pending']) + self::toInt($open_orders['steemOrders']);
			return [number_format(($total_steem * $price_per_steem + $total_sbd), 2), $total_steem, $price_per_steem, $total_sbd];
		}
	}

	/**
	 * process the orders of the account and return the total SBD and STEEM orders
	 * @param array $open_orders 
	 * @param int $precision 
	 * @return array
	 */
	public static function processOrders($open_orders, $precision)
	{
		$sbdOrders = 0;
		$steemOrders = 0;
		if (is_array($open_orders)) {
			foreach ($open_orders as $open_orders_key => $open_orders_value) {
				if (self::contains($open_orders_value['sell_price']['base'], "SBD")) {
					$sbdOrders += $open_orders_value['for_sale'] / $precision;
				} else if(self::contains($open_orders_value['sell_price']['base'], "STEEM")) {
					$steemOrders += $open_orders_value['for_sale'] / $precision;
				}
			}
		}
		return ['sbdOrders' => $sbdOrders, 'steemOrders' => $steemOrders];
	}

	/**
	 * calculate the total SBD and STEEM saving of the account
	 * @param array $savings_withdraws 
	 * @return array
	 */
	public static function calculateSaving($savings_withdraws)
	{
		$savings_pending = 0;
		$savings_sbd_pending = 0;
		if (is_array($savings_withdraws)) {
			foreach ($savings_withdraws as $savings_withdraws_key => $savings_withdraws_value) {
				$_withdraw_amount_split = explode(' ', $savings_withdraws_value['amount']);
				$amount = $_withdraw_amount_split[0];
				$asset = $_withdraw_amount_split[1];
				if ($asset == "STEEM") {
					$savings_pending += floatval($amount);
				} else if ($asset == "SBD") {
					$savings_sbd_pending += floatval($amount);
				}
			}
		}
		return ['savings_pending' => $savings_pending, 'savings_sbd_pending' => $savings_sbd_pending];
	}

	/**
	 * find the amount of steem the vests are worth
	 * @param int $vestingShares
	 * @param int $totalVestingFundSteem
	 * @param int $totalVestingShares
	 * @return int
	 */
	public static function vestToSteem($vestingShares, $totalVestingFundSteem, $totalVestingShares)
	{
		return floatval($totalVestingFundSteem) * floatval($vestingShares) / floatval($totalVestingShares);
	}

	/**
	 * check if a string contains the $data in it
	 * @param String $data 
	 * @param String $contains 
	 * @return int
	 */
	public static function contains($data, $contains)
	{
		return preg_match('/('.$contains.')/', $data);
	}

	/**
	 * get the character at $pos
	 * @param String $string 
	 * @param int $pos 
	 * @return String
	 */
	public static function charAt($string, $pos)
	{
		return $string{$pos};
	}

}



?>