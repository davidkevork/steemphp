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
	 * @var $prefix
	 * 
	 * $prefix is the address prefix for public keys
	 */
	public $prefix = "STM";

	/**
	 * Sets the address prefix.
	 *
	 * @param      string  $prefix  The prefix
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = trim($prefix);
	}

	/**
	 * password to private WIF
	 *
	 * @param      string  $name      The username
	 * @param      string  $password  The password
	 * @param      string  $role      The role
	 *
	 * @return     string  private wif
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

	/**
	 * Check if the given string is wif
	 *
	 * @param      string   $wif    The wif
	 *
	 * @return     boolean  True if wif, False otherwise.
	 */
	public function isWif($wif)
	{
		try {
			PrivateKeyFactory::fromWif($wif);
			return true;
		} catch(\Exception $e) {
			return false;
		}
	}

}

?>
