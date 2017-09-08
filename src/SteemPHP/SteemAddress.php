<?php

namespace SteemPHP;

use SteemPHP\SteemHelper;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Buffertools;
use BitWasp\Bitcoin\Base58;

/**
* SteemAddress
* 
* Addresses are shortened non-reversable hashes of a public key.  The full PublicKey is preferred.
*
* This Class contains functions for steem address
*/
class SteemAddress
{

	/**
	 * @var $address_prefix
	 * 
	 * $address_prefix is the address prefix for public keys
	 */
	public $address_prefix = "STM";

	/**
	 * @var $addy
	 */
	private $addy;

	/**
	 * construct a new SteemAddress
	 * 
	 * @param      string  $addy
	 * @param      string  $address_prefix  The address prefix
	 */
	public function __construct($addy = "", $address_prefix = "STM")
	{
		$this->addy = new Buffer(trim($addy));
		$this->setPrefix($address_prefix);
	}

	/**
	 * Sets the address prefix.
	 *
	 * @param	  string  $prefix  The address prefix
	 */
	public function setPrefix($address_prefix)
	{
		$this->address_prefix = trim($address_prefix);
	}

	/**
	 * SteemAddress from buffer
	 *
	 * @param      string  $buffer  The buffer
	 *
	 * @return     string  address from the given buffer
	 */
	public function fromBuffer($buffer)
	{
		$this->_hash = new Buffer(hash('sha512', $buffer));
		$this->addy = Hash::ripemd160($this->_hash);
		$SteemAddress = new SteemAddress($this->addy->getHex(), $this->address_prefix);
		return $SteemAddress->toString();
	}

	/**
	 * SteemAddress from a given public key
	 *
	 * @param      string  $string  The public key
	 *
	 * @return     string  address from a given public key
	 */
	public function fromString($string)
	{
		$prefix = SteemHelper::str_slice($string, 0, strlen($this->address_prefix));
		if ($prefix != $this->address_prefix) {
			return "Expecting key to begin with ".$this->address_prefix.", instead got ".$prefix;
		} else {
			$addy = SteemHelper::str_slice($string, strlen($this->address_prefix));
			$addy = new Buffer(Base58::decode($addy)->getHex());
			$checksum = $addy->slice(-4);
			$addy = $addy->slice(0, 4);
			$new_checksum = Hash::ripemd160($addy);
			$new_checksum = $new_checksum->slice(0, 4);
			if ($checksum->getHex() != $new_checksum->getHex())
			{
				return 'Checksum did not match';
			}
			$SteemAddress = new SteemAddress($addy->getHex(), $this->address_prefix);
			return $SteemAddress->toString();
		}
	}

	/**
	 * Get the addy in buffer format
	 * @return string
	 */
	public function toBuffer()
	{
		return $this->addy->getHex();
	}

	/**
	 * turn the addy into an address
	 * @return type
	 */
	public function toString()
	{
		$checksum = Hash::ripemd160($this->addy);
		$this->addy = Buffertools::concat($this->addy, $checksum->slice(0, 4));
		return $this->address_prefix . Base58::encode($this->addy);
	}

}

?>