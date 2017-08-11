<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemAccountTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemAccount = new \SteemPHP\SteemAccount('https://steemd.steemit.com');
	}

	public function testGetApi()
	{
		$this->assertInternalType('int', $this->SteemAccount->getApi('login_api'));
	}

	public function testGetProps()
	{
		$this->assertArrayHasKey('head_block_number', $this->SteemAccount->getProps());
	}

	public function testGetAccountHistory()
	{
		$this->assertArrayHasKey('trx_id', $this->SteemAccount->getAccountHistory('davidk', 2)[0][1]);
	}

	public function testGetAccount()
	{
		$this->assertArrayHasKey('name', $this->SteemAccount->getAccount('davidk')[0]);
	}

	public function testGetReputation()
	{
		$this->assertInternalType('int', $this->SteemAccount->getReputation('davidk'));
	}

	public function testVestToSteemByAccount()
	{
		$this->assertInternalType('float', $this->SteemAccount->vestToSteemByAccount('davidk'));
	}

	public function testGetFollowing()
	{
		$this->assertArrayHasKey('follower', $this->SteemAccount->getFollowing('davidk')[0]);
	}

	public function testGetFollowers()
	{
		$this->assertArrayHasKey('follower', $this->SteemAccount->getFollowers('davidk')[0]);
	}

	public function testCountFollows()
	{
		$this->assertArrayHasKey('account', $this->SteemAccount->countFollows('davidk'));
	}

	public function testGetAccountReputations()
	{
		$this->assertArrayHasKey('account', $this->SteemAccount->getAccountReputations('davidk', 2)[0]);
	}

}

?>