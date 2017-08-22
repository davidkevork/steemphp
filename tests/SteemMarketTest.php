<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemMarketTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemMarket = new \SteemPHP\SteemMarket('https://steemd.steemit.com');
	}

	public function testGetApi()
	{
		$this->assertInternalType('int', $this->SteemChain->getApi('login_api'));
	}

	public function testGetOrderBook()
	{
		$order = $this->SteemMarket->getOrderBook(5);
		$this->assertArrayHasKey('asks', $order);
		$this->assertArrayHasKey('bids', $order);
	}

	public function testGetOpenOrders()
	{
		$this->assertInternalType('array', $this->SteemMarket->getOpenOrders('david'));
	}

	public function testGetLiquidityQueue()
	{
		$this->assertInternalType('array', $this->SteemMarket->getLiquidityQueue('davidk', 5));
	}

	public function testGetOwnerHistory()
	{
		$this->assertInternalType('array', $this->SteemMarket->getOwnerHistory('davidk'));
	}

	public function testGetTicker()
	{
		$this->assertArrayHasKey('latest', $this->SteemMarket->getTicker());
	}

	public function testGetMarketHistory()
	{
		$this->assertInternalType('array', $this->SteemMarket->getMarketHistory('2017/7/11', '2017/8/11', 60));
	}

	public function testGetMarketHistoryBuckets()
	{
		$this->assertEquals('15', $this->SteemMarket->getMarketHistoryBuckets()[0]);
	}

	public function testGetOrderBookFromMarket()
	{
		$order = $this->SteemMarket->getOrderBookFromMarket();
		$this->assertArrayHasKey('asks', $order);
		$this->assertArrayHasKey('bids', $order);
	}

	public function testGetRecentTrades()
	{
		$this->assertArrayHasKey('date', $this->SteemMarket->getRecentTrades(5)[0]);
	}

	public function testGetTradeHistory()
	{
		$this->assertArrayHasKey('date', $this->SteemMarket->getTradeHistory('2017/8/10', '2017/8/11', 5)[0]);
	}

	public function testGetVolume()
	{
		$this->assertArrayHasKey('steem_volume', $this->SteemMarket->getVolume());
	}

}

?>