<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;

/**
* SteemChain
* 
* This Class is contains only Chain Methods
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
	 * @param String $host
	 * 
	 * $host = ['https://node.steem.ws', 'https://steemd.steemit.com']
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
		return $this->client->call(1, 'get_api_by_name', [$name]);
	}

	/**
	 * Get Current blockchain version with steem and fc revision
	 * @return array
	 */
	public function getVersion()
	{
		$this->api = $this->getApi('login_api');
		return $this->client->call($this->api, 'get_version', []);
	}

	/**
	 * Get the total number of registered steem accounts
	 * @return int
	 */
	public function getAccountCount()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_account_count', []);
	}

	/**
	 * Get currency blockchain properties
	 * @return array
	 */
	public function getChainProperties()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_chain_properties', []);
	}

	/**
	 * Get blockchain configuration
	 * @return array
	 */
	public function getConfig()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_config', []);
	}

	/**
	 * Get Dynamic Global Properties
	 * @return array
	 */
	public function getDynamicGlobalProperties()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_dynamic_global_properties', []);
	}

	/**
	 * Get Feed History
	 * @return array
	 */
	public function getFeedHistory()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_feed_history', []);
	}

	/**
	 * Get Current Median History Price
	 * @return array
	 */
	public function getCurrentMeidanHistoryPrice()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_current_median_history_price', []);
	}

	/**
	 * Get current Hardfork version
	 * @return version
	 */
	public function getHardforkVersion()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_hardfork_version', []);
	}

	/**
	 * Get next scheduled hardfork version and date
	 * @return array
	 */
	public function getNextScheduledHardfork()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_next_scheduled_hardfork', []);
	}

}

?>