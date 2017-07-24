<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;
use SteemPHP\SteemHelper;

/**
* SteemConnection
* 
* SteemConnection includes basic account functions
*/
class SteemConnection
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
		try {
			return $this->client->call(1, 'get_api_by_name', [$name]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Dynamic Global Properties
	 * @return array
	 */
	public function getProps()
	{
		try {
			$return = $this->client->call(0, 'get_dynamic_global_properties', []);
			$return['steem_per_mvests'] = floor(SteemHelper::toInt($return['total_vesting_fund_steem']) / SteemHelper::toInt($return['total_vesting_shares']) * 1000000 * 1000) / 1000;
			return $return;
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Account History from: - account creation
	 *                       - account update
	 *                       - submited stories
	 *                       - comment
	 *                       - votes
	 *                       - followers
	 *                       - following
	 *                       - transfer
	 * @param String $username 
	 * @param int $limit 
	 * @param int $skip 
	 * @return array
	 */
	public function getAccountHistory($username, $limit = 100, $skip = -1)
	{
		try {
			return $this->client->call(0, 'get_account_history', [$username, SteemHelper::filterInt($skip), SteemHelper::filterInt($limit)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Account Information
	 * @param String $account 
	 * @return array
	 */
	public function getAccount($account)
	{
		try {
			$this->return = $this->client->call(0, 'get_accounts', [[$account]]);
			foreach($this->return as $this->index => $this->username) {
				$this->return[$this->index]['profile'] = json_decode($this->username['json_metadata'], true)['profile'];
			}
			return $this->return;
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Reputation of $account
	 * @param String $account 
	 * @return array
	 */
	public function getReputation($account)
	{
		try {
			$this->accountDetails = $this->getAccount($account);
			return SteemHelper::reputation($this->accountDetails[0]['reputation']);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get list of accounts that are similar to $account name and their reputations
	 * @param String $account 
	 * @param int $limit 
	 * @return array
	 */
	public function getAccountReputations($account, $limit = 100)
	{
		try {
			$this->api = $this->getApi('follow_api');
			$this->return = $this->client->call($this->api, 'get_account_reputations', [$account, SteemHelper::filterInt($limit)]);
			return $this->return;
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get the amount of steem $account's vest is worth
	 * @param String $account 
	 * @return array
	 */
	public function vestToSteemByAccount($account)
	{
		try {
			$this->accountDetails = $this->getAccount($account);
			$this->Props = $this->getProps();
			return SteemHelper::vestToSteem($this->accountDetails[0]['vesting_shares'], $this->Props['total_vesting_shares'], $this->Props['total_vesting_fund_steem']);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get list of people the $account is following
	 * @param String $account 
	 * @param int $limit 
	 * @param int $skip 
	 * @return array
	 */
	public function getFollowing($account, $limit = 100, $skip = -1)
	{
		try {
			$this->api = $this->getApi('follow_api');
			return $this->client->call($this->api, 'get_following', [$account, SteemHelper::filterInt($skip), 'blog', SteemHelper::filterInt($limit)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get list of people following $account
	 * @param String $account
	 * @param int $limit
	 * @param int $skip
	 * @return array
	 */
	public function getFollowers($account, $limit = 100, $skip = -1)
	{
		try {
			$this->api = $this->getApi('follow_api');
			return $this->client->call($this->api, 'get_followers', [$account, SteemHelper::filterInt($skip), 'blog', SteemHelper::filterInt($limit)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * count the number of followers and following of $account
	 * @param String $account 
	 * @return array
	 */
	public function countFollows($account)
	{
		try {
			$this->api = $this->getApi('follow_api');
			return $this->client->call($this->api, 'get_follow_count', [$account]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * get the estimated account value of $account
	 * 
	 * $state = $SteemArticle->getState('/@'.$account.'/transfers')
	 * $market = $SteemMaket->getOpenOrders($account);
	 * 
	 * NOTE: This function only gets the estimated amount of money inside the $accounts wallet
	 * 
	 * @param array $state 
	 * @param array $openOrders 
	 * @param String $account 
	 * @return int on success
	 * @return array on failure
	 */
	public function estimateAccountValue($state, $openOrders, $account)
	{
		try {
			return SteemHelper::estimateAccountValue($state, $openOrders, $account);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

}

?>