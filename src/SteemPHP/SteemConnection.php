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
		return $this->client->call(1, 'get_api_by_name', [$name]);
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
		} catch (Exception $e) {
			return  $e->getMessage();
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
		$this->username = trim($username);
		$this->limit = filter_var($limit, FILTER_VALIDATE_INT);
		$this->skip = $skip;
		try {
			return $this->client->call(0, 'get_account_history', [$this->username, $this->skip, $this->limit]);
		} catch (Exception $e) {
			return $e->getMessage();
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
			try {
				foreach($this->return as $this->index => $this->username) {
					$this->return[$this->index]['profile'] = json_decode($this->username['json_metadata'], true)['profile'];
				}
			} catch (Exception $e) {
				return $this->return;
			}
			return $this->return;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Get Reputation of $account
	 * @param String $account 
	 * @return array
	 */
	public function getReputation($account)
	{
		$this->account = trim($account);
		try {
			$this->accountDetails = $this->getAccount($this->account);
			return SteemHelper::reputation($this->accountDetails[0]['reputation']);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Get the amount of steem $account's vest is worth
	 * @param String $account 
	 * @return array
	 */
	public function vestToSteemByAccount($account)
	{
		$this->account = trim($account);
		try {
			$this->accountDetails = $this->getAccount($this->account);
			$this->Props = $this->getProps();
			return SteemHelper::vestToSteem($this->accountDetails[0]['vesting_shares'], $this->Props['total_vesting_shares'], $this->Props['total_vesting_fund_steem']);
		} catch (Exception $e) {
			return $e->getMessage();
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
		$this->account = trim($account);
		$this->limit = filter_var($limit, FILTER_VALIDATE_INT);
		$this->skip = filter_var($skip, FILTER_VALIDATE_INT);
		try {
			$this->api = $this->getApi('follow_api');
			return $this->client->call($this->api, 'get_following', [$this->account, $this->skip, 'blog', $this->limit]);
		} catch (Exception $e) {
			return $e->getMessage();
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
		$this->account = trim($account);
		$this->limit = filter_var($limit, FILTER_VALIDATE_INT);
		$this->skip = filter_var($skip, FILTER_VALIDATE_INT);
		try {
			$this->api = $this->getApi('follow_api');
			return $this->client->call($this->api, 'get_followers', [$this->account, $this->skip, 'blog', $this->limit]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * count the number of followers and following of $account
	 * @param String $account 
	 * @return array
	 */
	public function countFollows($account)
	{
		$this->account = trim($account);
		try {
			$this->api = $this->getApi('follow_api');
			return $this->client->call($this->api, 'get_follow_count', [$this->account]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Get the list of trending tags after $afteTag
	 * @param String $afterTag 
	 * @param int $limit 
	 * @return array
	 */
	public function getTrendingTags($afterTag, $limit = 100)
	{
		$this->afterTag = trim($afterTag);
		$this->limit = filter_var($limit, FILTER_VALIDATE_INT);
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_trending_tags', [$this->afterTag, $this->limit]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Get Content of an article
	 * @param String $author 
	 * @param String $permlink 
	 * @return array
	 */
	public function getContent($author, $permlink)
	{
		$this->author = trim($author);
		$this->permlink = trim($permlink);
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_content', [$this->author, $this->permlink]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Get Replies on an article
	 * @param String $author 
	 * @param String $permlink 
	 * @return array
	 */
	public function getContentReplies($author, $permlink)
	{
		$this->author = trim($author);
		$this->permlink = trim($permlink);
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_content_replies', [$this->author, $this->permlink]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

}

?>