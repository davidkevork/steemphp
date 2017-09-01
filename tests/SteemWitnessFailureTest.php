<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemWitnessFailureTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemWitness = new \SteemPHP\SteemWitness('https://rpc.google.com/');
	}

	public function testGetApi()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getApi('login_api'));
	}

	public function testGetWitnessCount()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getWitnessCount());
	}

	public function testLookupWitnessAccounts()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->lookupWitnessAccounts('admin', 10));
	}

	public function testGetWitnessSchedule()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getWitnessSchedule());
	}

	public function testGetWitnesses()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getWitnesses('1'));
	}

	public function testGetWitnessByAccount()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getWitnessByAccount('admin'));
	}

	public function testGetWitnessesByVote()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getWitnessesByVote('good-karma', 1));
	}

	public function testGetActiveWitnesses()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getActiveWitnesses());
	}

	public function testGetMinerQueue()
	{
		$this->assertArrayHasKey('instance', $this->SteemWitness->getMinerQueue());
	}

}

?>