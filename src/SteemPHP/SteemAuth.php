<?php

namespace SteemPHP;

use SteemPHP\SteemHelper;
use SteemPHP\SteemPrivate;
use SteemPHP\SteemPublic;

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
	 * Gets the private keys.
	 *
	 * @param      string  $name      The name
	 * @param      string  $password  The password
	 *
	 * @return     arrray  The private keys.
	 */
	public function getPrivateKeys($name, $password)
	{
		$this->role = ['owner', 'active', 'posting', 'memo'];
		$this->key = [];
		$SteemPrivate = new SteemPrivate;
		$SteemPublic = new SteemPublic;
		foreach ($this->role as $value) {
			$this->key[$value] = $SteemPrivate->toWif($name, $password, $value);
			$this->key[$value.'Pubkey'] = ($this->key[$value]);
		}
		return $this->key;
	}

	/**
	 * Nomralize the brain key
	 *
	 * @param      string 		 $brain_key  The brain key
	 *
	 * @return     array|string  array on failure|string on success
	 */
	public function normalize($brain_key)
	{
		if (gettype($brain_key) != "string") {
			return ['error' => 'string required for brain_key'];
		} else {
			return implode(" ", explode("/[\t\n\v\f\r ]+/", trim($brain_key)));
		}
	}

}

?>