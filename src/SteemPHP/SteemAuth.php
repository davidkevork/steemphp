<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;
use SteemPHP\SteemHelper;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Key\PrivateKeyFactory;

/**
* SteemAuth
* 
* This Class contains functions for steem auth
*/
class SteemAuth
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
	 * password to private WIF
	 *
	 * @param      <string>  $name      The username
	 * @param      <string>  $password  The password
	 * @param      <string>  $role      The role
	 *
	 * @return     <string>  ( private wif )
	 */
	public function toWif($name, $password, $role)
	{
		$seed = $name.$role.$password;
		$brainKey = implode(" ", explode("/[\t\n\v\f\r ]+/", trim($seed)));
		$hashSha256 = hash('sha256', $brainKey);
		$privKey = PrivateKeyFactory::fromHex($hashSha256);
		$privWif = $privKey->toWif();
		return $privWif;
	}

}

?>