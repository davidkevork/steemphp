<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;
use SteemPHP\SteemHelper;

/**
* SteemMarket
* 
* This Class contains functions for steem market
*/
class SteemMarket
{

	/**
	 * @var $host
	 * 
	 * $host will be where our script will connect to fetch the data
	 */
	protected $host;

	/**
	 * @var $client
	 * 
	 * $client is part of JsonRPC which will be used to connect to the server
	 */
	protected $client;

	/**
	 * Initialize the connection to the host
	 * 
	 * @param      string  $host   The node you want to connect
	 */
	public function __construct($host = 'https://steemd.steemit.com')
	{
		$this->host = trim($host);
		$this->httpClient = new HttpClient($this->host);
		$this->httpClient->withoutSslVerification();
		$this->client = new Client($this->host, false, $this->httpClient);
	}

	/**
	 * Gets the api number by api $name
	 *
	 * @param      sting  $name   The name of the api
	 *
	 * @return     integer        The api number
	 */
	public function getApi($name)
	{
		try{
			return $this->client->call(1, 'get_api_by_name', [$name]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get the list of sell & buy for STEEM & SBD
	 *
	 * @param      integer  $limit  The limit
	 *
	 * @return     array    The order book.
	 */
	public function getOrderBook($limit = 100)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_order_book', [SteemHelper::filterInt($limit)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get open orders for $account
	 *
	 * @param      string  $account  The account name
	 *
	 * @return     array   The open orders of $account.
	 */
	public function getOpenOrders($account)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_open_orders', [$account]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get liquidity reward queue starting from $startAccount and get upto $limit list
	 *
	 * @param      string   $startAccount  The start account
	 * @param      integer  $limit         The limit
	 *
	 * @return     array    The liquidity queue.
	 */
	public function getLiquidityQueue($startAccount, $limit = 100)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_liquidity_queue', [$startAccount, $limit]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Gets the owner history of $account.
	 *
	 * @param      string  $account  The account
	 *
	 * @return     array   The owner history.
	 */
	public function getOwnerHistory($account)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_owner_history', [$account]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Gets the market ticker for the internal SBD:STEEM market
	 *
	 * @return     array   The ticker.
	 */
	public function getTicker()
	{
		try {
			$this->api = $this->getApi('market_history_api');
			return $this->client->call($this->api, 'get_ticker', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

    /**
     * Gets the market history for the internal SBD:STEEM market.
     *
     * @param      date|time  $startTime       The start time to get market history.
     * @param      date|time  $endTime         The end time to get market history
     * @param      integer    $bucket_seconds  The size of buckets the history is broken into.
     *                                         The bucket size must be configured in the plugin options.
     *
     * @return     array      A list of market history buckets.
     */
	public function getMarketHistory($startTime, $endTime, $bucket_seconds)
	{
		$this->bucket_seconds = $bucket_seconds;
		$this->startTime = SteemHelper::filterDate($startTime);
		$this->endTime = SteemHelper::filterDate($endTime);
		try {
			$this->api = $this->getApi('market_history_api');
			return $this->client->call($this->api, 'get_market_history', [$this->bucket_seconds, $this->startTime, $this->endTime]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Gets the bucket seconds being tracked by the plugin.
	 *
	 * @return     array   The market history buckets.
	 */
	public function getMarketHistoryBuckets()
	{
		try {
			$this->api = $this->getApi('market_history_api');
			return $this->client->call($this->api, 'get_market_history_buckets', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Gets the current order book for the internal SBD:STEEM market.
	 *
	 * @param      integer  $limit  The number of orders to have on each side of the order book. 
	 *                              Maximum is 500
	 *
	 * @return     array    The order book from market.
	 */
	public function getOrderBookFromMarket($limit = 100)
	{
		try {
			$this->api = $this->getApi('market_history_api');
			return $this->client->call($this->api, 'get_order_book', [SteemHelper::filterInt($limit)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

    /**
     * Gets the N most recent trades for the internal SBD:STEEM market.
     *
     * @param      integer  $limit  The number of recent trades to return.
     *                              Maximum is 1000.
     *
     * @return     array    A list of completed trades.
     */
	public function getRecentTrades($limit = 100)
	{
		try {
			$this->api = $this->getApi('market_history_api');
			return $this->client->call($this->api, 'get_recent_trades', [SteemHelper::filterInt($limit)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Gets the trade history for the internal SBD:STEEM market.
	 *
	 * @param      date|time  $startTime  The start time of the trade history.
	 * param       date|time  $endTime    The end time of the trade history.
	 * @param      integer    $limit      The number of trades to return. Maximum is 1000.
	 *
	 * @return     array      A list of completed trades.
	 */
	public function getTradeHistory($startTime, $endTime, $limit = 100)
	{
		$this->limit = SteemHelper::filterInt($limit);
		$this->startTime = SteemHelper::filterDate($startTime);
		$this->endTime = SteemHelper::filterDate($endTime);
		try {
			$this->api = $this->getApi('market_history_api');
			return $this->client->call($this->api, 'get_trade_history', [$this->startTime, $this->endTime, $this->limit]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Gets the market volume for the past 24 hours
	 *
	 * @return     array   The market volume.
	 */
	public function getVolume()
	{
		try {
			$this->api = $this->getApi('market_history_api');
			return $this->client->call($this->api, 'get_volume', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

}

?>