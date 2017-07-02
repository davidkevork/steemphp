<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemConnectionTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemConnection = new \SteemPHP\SteemConnection('https://steemd.steemit.com');
	}

	public function testGetApi()
	{
		$this->assertInternalType('int', $this->SteemConnection->getApi('login_api'));
	}

	public function testGetProps()
	{
		$this->assertArrayHasKey('head_block_number', $this->SteemConnection->getProps());
	}

	public function testGetAccountHistory()
	{
		$this->assertArrayHasKey('trx_id', $this->SteemConnection->getAccountHistory('davidk', 2)[0][1]);
	}

	public function testGetAccount()
	{
		$this->assertArrayHasKey('name', $this->SteemConnection->getAccount('davidk')[0]);
	}

	public function testGetReputation()
	{
		$this->assertInternalType('int', $this->SteemConnection->getReputation('davidk'));
	}

	public function testVestToSteemByAccount()
	{
		$this->assertInternalType('float', $this->SteemConnection->vestToSteemByAccount('davidk'));
	}

	public function testGetFollowing()
	{
		$this->assertArrayHasKey('follower', $this->SteemConnection->getFollowing('davidk')[0]);
	}

	public function testGetFollowers()
	{
		$this->assertArrayHasKey('follower', $this->SteemConnection->getFollowers('davidk')[0]);
	}

	public function testCountFollows()
	{
		$this->assertArrayHasKey('account', $this->SteemConnection->countFollows('davidk'));
	}

	public function testGetAccountReputations()
	{
		$this->assertArrayHasKey('account', $this->SteemConnection->getAccountReputations('davidk', 2)[0]);
	}

}

?>