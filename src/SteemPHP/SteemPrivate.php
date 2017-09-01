<?php

namespace SteemPHP;

use SteemPHP\SteemHelper;
use BitWasp\Bitcoin\Key\PrivateKeyFactory;

/**
* SteemPrivate
* 
* This Class contains functions for steem private key
*/
class SteemPrivate
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
	 * @return     array   private wif
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
	 * Get Private Key / WIF from seed
	 *
	 * @param      string  $seed   The seed
	 *
	 * @return     array   private wif
	 */
	public function fromSeed($seed)
	{
		if (gettype($seed) != "string") {
			return ['error' => 'string required for seed'];
		} else {
			return PrivateKeyFactory::fromHex(hash('sha256', $seed))->toWif();
		}
	}

	/**
	 * Get Private Key / WIF from a buffer
	 * buffer is the same as hex
	 *
	 * @param      string         $buffer  The buffer/hex
	 *
	 * @return     array|string   array on failure|wif on success
	 */
	public function fromBuffer($buffer)
	{
		if (!Buffer::hex($buffer)) {
			return ['error' => 'Expecting paramter to be a Buffer type'];
		} else if (strlen($buffer) == 32) {
			return ['error' => 'Empty Buffer'];
		} else {
			return PrivateKeyFactory::fromHex($buffer)->toWif();
		}
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

	/**
	 * Get wif from wif
	 *
	 * @param      string  $wif    The wif
	 *
	 * @return     string  The wif
	 */
	public function fromWif($wif)
	{
		return PrivateKeyFactory::fromWif($wif)->toWif();
	}

}

?>