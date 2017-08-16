<?php

namespace SteemPHP;

/**
 * Steem Helper Library
 */
trait SteemHelper
{

	/**
	 * removes non-numeric characters and returns just numbers
	 *
	 * @param      string   $string  The string
	 *
	 * @return     integer  the integer from the string
	 */
	public static function toInt($string)
	{
		return preg_replace('/[^0-9\.]/', '', $string);
	}

	/**
	 * Checks if an input is integer or not
	 *
	 * @param      integer  $int    The int
	 *
	 * @return     boolean  true of int, false otherwise
	 */
	public static function filterInt($int)
	{
		return filter_var($int, FILTER_VALIDATE_INT);
	}

	/**
	 * Changes timestamp or date to format('Y-m-d\TH-i-s')
	 *
	 * @param      date/timestamp  $date   The date
	 *
	 * @return     date            The date in the format
	 */
	public static function filterDate($date)
	{
		$date = strtotime($date) ? $date : date('Y-m-d H:i:s', $date);
		$dt = new \DateTime($date);
		$dt->setTimeZone(new \DateTimeZone('UTC'));
		return $dt->format('Y-m-d\TH-i-s');
	}

	/**
	 * calculated the reputation from the raw reputation
	 *
	 * @param      integer   $rep    The rar reputation
	 *
	 * @return     integer   The repution
	 */
	public static function reputation($rep)
	{
		if (is_null($rep) || !is_numeric($rep)) {
			return 0;
		} else {
			$e = $rep;
			$r =  $e < 0 ? true : false;
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
	 * NOTE: this code is broken and only calculated the amount of money the user currently has
	 * do not use until we fix it
	 *
	 * @param      array         $data     The data
	 * @param      array         $market   The market
	 * @param      array         $account  The account
	 *
	 * @return     array|null    If correct data is not supplied will return null
	 */
	public static function estimateAccountValue($data, $market, $account)
	{
		if (is_null($data) && !is_array($data)) {
			return null;
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
	 * Process the orders of the accoutn and return the total SBD and STEEM orders
	 *
	 * @param      array    $open_orders  The open orders
	 * @param      integer  $precision    The precision
	 *
	 * @return     array    sbdOrders and steemOrders
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
	/**
	 * Calculate the toal SBD and STEEM saving of the account
	 *
	 * @param      array  $savings_withdraws  The savings withdraws
	 *
	 * @return     array   The saving.
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
	 * Find the amount of steem the vests are worth
	 *
	 * @param      integer  $vestingShares          The vesting shares
	 * @param      integer  $totalVestingFundSteem  The total vesting fund steem
	 * @param      integer  $totalVestingShares     The total vesting shares
	 *
	 * @return     integer  The amount of steem worth
	 */
	public static function vestToSteem($vestingShares, $totalVestingFundSteem, $totalVestingShares)
	{
		return floatval($totalVestingFundSteem) * floatval($vestingShares) / floatval($totalVestingShares);
	}

	/**
	 * Checks if the $contains is found in the $data
	 *
	 * @param      string   $data      The data
	 * @param      string   $contains  The char that it contains
	 *
	 * @return     integer  1 if contains, 0 otherwise
	 */
	public static function contains($data, $contains)
	{
		return preg_match('/('.$contains.')/', $data);
	}

	/**
	 * Get the character at the position $pos
	 *
	 * @param      string   $string  The string
	 * @param      integer  $pos     The position
	 *
	 * @return     string   The character at that position
	 */
	public static function charAt($string, $pos)
	{
		return $string{$pos};
	}

	/**
	 * Handle exceptions thrown while running the script
	 *
	 * @param      Exception  $e      The exception that is catched
	 *
	 * @return     array  	  details of the exception
	 */
	public static function handleError($e)
	{
		if (!is_object($e)) {
			return [];
		} else {
			$instance = 'Exception';
			if ($e instanceof \JsonRPC\Exception\ResponseException) {
				$instance = 'JsonRPC\Exception\ResponseException';
			} else if ($e instanceof \JsonRPC\Exception\ConnectionFailureException) {
				$instance = 'JsonRPC\Exception\ConnectionFailureException';
			} else if ($e instanceof \JsonRPC\Exception\InvalidJsonFormatException) {
				$instance = 'JsonRPC\Exception\InvalidJsonFormatException';
			} else if ($e instanceof \JsonRPC\Exception\ServerErrorException) {
				$instance = 'JsonRPC\Exception\ServerErrorException';
			} else if ($e instanceof \JsonRPC\Exception\ResponseEncodingFailureException) {
				$instance = 'JsonRPC\Exception\ResponseEncodingFailureException';
			}
			return ['instance' => $instance,
					'message' => $e->getMessage(),
					'file' => $e->getFile(),
					'line' => $e->getLine(),
					'trace' => $e->getTrace()];
		}
	}

}


?>