<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;
use SteemPHP\SteemHelper;

/**
* SteemConnection
* 
* All the Calls will be called from this class as it will have most of the functions
*/
class SteemWitness
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
		return $this->client->call(1, 'get_api_by_name', [$name]);
	}

	/**
	 * Get total withness count
	 * @return int
	 */
	public function getWitnessCount()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_witness_count', []);
	}

	/**
	 * Get list of witness account names similar to $account
	 * @param String $account 
	 * @param int $limit 
	 * @return array
	 */
	public function lookupWitnessAccounts($lowerBoundName, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'lookup_witness_accounts', [$lowerBoundName, SteemHelper::filterInt($limit)]);
	}

	/**
	 * Get the next witness schedule
	 * @return array
	 */
	public function getWitnessSchedule()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_witness_schedule', []);
	}

	/**
	 * Get witness information by ID
	 * @param int|array $accounts 
	 * @return array
	 */
	public function getWitnesses($accounts = [])
	{
		$accounts = !is_array($accounts) ? [$accounts] : $accounts;
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_witnesses', [$accounts]);
	}

	/**
	 * Get witness information by account name
	 * @param String $account
	 * @return array
	 */
	public function getWitnessByAccount($account)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_witness_by_account', [$account]);
	}

	/**
	 * Get Witnesses by vote where the names are similar to $account name
	 * @param String $account 
	 * @param int $limit 
	 * @return array
	 */
	public function getWitnessesByVote($account, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_witnesses_by_vote', [$account, SteemHelper::filterInt($limit)]);
	}

	/**
	 * Get list of active witnesses
	 * @return array
	 */
	public function getActiveWitnesses()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_active_witnesses', []);
	}

	/**
	 * Get list of miners queue
	 * @return array
	 */
	public function getMinerQueue()
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_miner_queue', []);
	}

}

?>