<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;
use SteemPHP\SteemHelper;

/**
* SteemAccount
* 
* SteemAccount includes basic account functions
*/
class SteemAccount
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
		try {
			return $this->client->call(1, 'get_api_by_name', [$name]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Gets Dynamic Global Properties
	 *
	 * @return     array   The properties
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
	 * Gets the account history.
	 *
	 * @param      string   $username  The username
	 * @param      integer  $limit     The limit
	 * @param      integer  $skip      Skip is the place to start for pagination
	 *
	 * @return     array   The account history.
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
	 * Gets the account information.
	 *
	 * @param      string  $account  The account name
	 *
	 * @return     array   The account information
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
	 * Gets the reputation of an account.
	 *
	 * @param      string   $account  The account name
	 *
	 * @return     integer  The reputation.
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
	 * Gets the account reputations of accounts having $account as lower bound
	 *
	 * @param      string   $account  The account name
	 * @param      integer  $limit    The limit
	 *
	 * @return     array   The account reputations.
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
	 *
	 * @param      string  $account  The account
	 *
	 * @return     integer  steem amount
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
	 *
	 * @param      string   $account  The account name
	 * @param      integer  $limit    The limit
	 * @param      integer  $skip     Skip is the place to start for pagination
	 *
	 * @return     arra     The following.
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
	 * Get the followers list for $account
	 *
	 * @param      string   $account  The account name
	 * @param      integer  $limit    The limit
	 * @param      integer  $skip     Skip is the place to start for pagination
	 *
	 * @return     array    The followers.
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
	 * Count the number of follows and followers of $account
	 *
	 * @param      string  $account  The account name
	 *
	 * @return     array   Number of follows.
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
	 * Get the estimated account value of $account
	 * 
	 * $state = $SteemArticle->getState('/@'.$account.'/transfers')
	 * $market = $SteemMaket->getOpenOrders($account);
	 * 
	 * NOTE: This function only gets the estimated amount of money inside the $accounts wallet
	 * 
	 * @param      array  $state       The state
	 * @param      array  $openOrders  The open orders
	 * @param      string  $account     The account
	 * 
	 * if (success) {
	 * 		@return     integer  estimated account value
	 * } else {
	 * 		@return 	array    the error that it catches
	 * }
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