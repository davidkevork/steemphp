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
	 * @param String $host
	 * 
	 * $host = ['https://steemd.steemitdev.com', 'https://steemd.steemit.com']
	 */
	public function __construct($host = 'https://steemd.steemit.com')
	{
		$this->host = trim($host);
		$this->httpClient = new HttpClient($this->host);
		$this->httpClient->withoutSslVerification();
		$this->client = new Client($this->host, false, $this->httpClient);
	}

	/**
	 * Get Api number
	 * @param String $name 
	 * @return int
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
	 * @param int $limit 
	 * @return array
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
	 * @param String $account 
	 * @return array
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
	 * Get liquidity reward Queue starting from $startAccount and get upto $limit list
	 * @param String $startAccount 
	 * @param int $limit 
	 * @return array
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
	 * Get owner history
	 * @param String $account 
	 * @return array
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

}

?>