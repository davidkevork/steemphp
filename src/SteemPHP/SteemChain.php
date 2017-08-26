<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;

/**
* SteemChain
* 
* This Class is contains only Steemit BlockChain Methods
*/
class SteemChain
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
	public function __construct($host = 'https://node.steem.ws')
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
		try {
			return $this->client->call(1, 'get_api_by_name', [$name]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Current blockchain version with steem and fc revision
	 * @return array
	 */
	public function getVersion()
	{
		try {
			$this->api = $this->getApi('login_api');
			return $this->client->call($this->api, 'get_version', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get the total number of registered steem accounts
	 * @return int
	 */
	public function getAccountCount()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_account_count', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get currency blockchain properties
	 * @return array
	 */
	public function getChainProperties()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_chain_properties', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get blockchain configuration
	 * @return array
	 */
	public function getConfig()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_config', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Dynamic Global Properties
	 * @return array
	 */
	public function getDynamicGlobalProperties()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_dynamic_global_properties', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Feed History
	 * @return array
	 */
	public function getFeedHistory()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_feed_history', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Current Median History Price
	 * @return array
	 */
	public function getCurrentMeidanHistoryPrice()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_current_median_history_price', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get current Hardfork version
	 * @return version
	 */
	public function getHardforkVersion()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_hardfork_version', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get next scheduled hardfork version and date
	 * @return array
	 */
	public function getNextScheduledHardfork()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_next_scheduled_hardfork', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

}

?>