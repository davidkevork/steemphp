<?php

include (__DIR__).'/../vendor/autoload.php';

use SteemPHP\SteemHelper;

class SteemHelperTest extends PHPUnit_Framework_TestCase
{

	public function testToInt()
	{
		$this->assertInternalType('string', SteemHelper::toInt('500 STEEM'));
	}

	public function testNodes()
	{
		$this->assertEquals(['https://gtg.steem.house:8090',
				'https://steemd.steemitdev.com',
				'https://steemd.steemit.com',
				'https://steemd-int.steemit.com',
				'https://seed.bitcoiner.me',
				'https://steemd.privex.io'], SteemHelper::nodes());
	}

	public function testFilterInt()
	{
		$this->assertInternalType('int', SteemHelper::filterInt(500));
	}

	public function testReputation()
	{
		$this->assertInternalType('int', SteemHelper::reputation(3000000));
	}

	public function testFilterDate()
	{
		$this->assertEquals('2017-06-30T00-00-00', SteemHelper::filterDate('2017-06-30'));
	}

	public function testVestToSteem()
	{
		$this->assertInternalType('float', SteemHelper::vestToSteem('24477.640182 VESTS', '369735200112.175967 VESTS', '178661603.552 STEEM'));
	}

	public function testContains()
	{
		$this->assertEquals('1', SteemHelper::contains('davidk', 'd'));
	}

	public function testCharAt()
	{
		$this->assertEquals('a', SteemHelper::charAt('abcd', 0));
	}

	public function testHandleError()
	{
		$this->assertEquals([], SteemHelper::handleError(''));
	}

	public function testStr_slice()
	{
		$this->assertEquals('abc', SteemHelper::str_slice('abcdefg', 0, 3));
	}

	public function testSlice()
	{
		$this->assertEquals(['result' => ['d','e','f','g']] ,SteemHelper::slice(['a','b','c','d','e','f','g'], -4, 7));
	}

}

?>