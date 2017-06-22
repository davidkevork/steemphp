<?php

namespace steem;

use JsonRPC\Client;
use JsonRPC\HttpClient;

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
	 */
	public function __construct($host)
	{
		$this->host = trim($host);
		$this->httpClient = new HttpClient($this->host);
		$this->httpClient->withoutSslVerification();
		$this->client = new Client($this->host, false, $this->httpClient);
	}

	/**
	 * __toString() will return JsonRPC data
	 * @return $this
	 */
	public function __toString()
	{
		echo '<pre>';
		print_r($this);
		echo '</pre>';
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
		$this->skip = filter_var($skip, FILTER_VALIDATE_INT);
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
	 * Get Api number
	 * @param String $name 
	 * @return int
	 */
	public function getApi($name)
	{
		return $this->client->call(1, 'get_api_by_name', [$name]);
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

}

?>