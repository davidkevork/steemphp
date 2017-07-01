<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemChainTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemChain = new \SteemPHP\SteemChain('https://steemd.steemit.com');
	}

	public function testGetApi()
	{
		$this->assertInternalType('int', $this->SteemChain->getApi('login_api'));
	}

	public function testGetVersion()
	{
		$this->assertArrayHasKey('blockchain_version', $this->SteemChain->getVersion());
	}

	public function testGetAccountCount()
	{
		$this->assertInternalType('int', $this->SteemChain->getAccountCount());
	}

	public function testGetChainProperties()
	{
		$this->assertArrayHasKey('account_creation_fee', $this->SteemChain->getChainProperties());
	}

	public function testGetConfig()
	{
		$this->assertArrayHasKey('SBD_SYMBOL', $this->SteemChain->getConfig());
	}

	public function testGetDynamicGlobalProperties()
	{
		$this->assertArrayHasKey('head_block_id', $this->SteemChain->getDynamicGlobalProperties());
	}

	public function testGetFeedHistory()
	{
		$this->assertArrayHasKey('id', $this->SteemChain->getFeedHistory());
	}

	public function testGetCurrentMeidanHistoryPrice()
	{
		$this->assertArrayHasKey('base', $this->SteemChain->getCurrentMeidanHistoryPrice());
	}

	public function testGetHardforkVersion()
	{
		$this->assertInternalType('string', $this->SteemChain->getHardforkVersion());
	}

	public function testGetNextScheduledHardfork()
	{
		$this->assertArrayHasKey('hf_version', $this->SteemChain->getNextScheduledHardfork());
	}
}

?>