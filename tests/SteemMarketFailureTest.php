<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemMarketFailureTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemMarket = new \SteemPHP\SteemMarket('https://rpc.google.com/');
	}

	public function testGetApi()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getApi('login_api'));
	}

	public function testGetOrderBook()
	{
		$order = $this->SteemMarket->getOrderBook(5);
		$this->assertArrayHasKey('instance', $order);
	}

	public function testGetOpenOrders()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getOpenOrders('david'));
	}

	public function testGetLiquidityQueue()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getLiquidityQueue('davidk', 5));
	}

	public function testGetOwnerHistory()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getOwnerHistory('davidk'));
	}

	public function testGetTicker()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getTicker());
	}

	public function testGetMarketHistory()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getMarketHistory('2017/7/11', '2017/8/11', 60));
	}

	public function testGetMarketHistoryBuckets()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getMarketHistoryBuckets());
	}

	public function testGetOrderBookFromMarket()
	{
		$order = $this->SteemMarket->getOrderBookFromMarket();
		$this->assertArrayHasKey('instance', $order);
	}

	public function testGetRecentTrades()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getRecentTrades(5));
	}

	public function testGetTradeHistory()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getTradeHistory('2017/8/10', '2017/8/11', 5));
	}

	public function testGetVolume()
	{
		$this->assertArrayHasKey('instance', $this->SteemMarket->getVolume());
	}

}

?>