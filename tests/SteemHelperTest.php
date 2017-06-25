<?php

include (__DIR__).'/../vendor/autoload.php';

use SteemPHP\SteemHelper;

class SteemHelperTest extends PHPUnit_Framework_TestCase
{

	public function testToInt()
	{
		$this->assertInternalType('string', SteemHelper::toInt('500 STEEM'));
	}

	public function test_filter_int()
	{
		$this->assertInternalType('int', SteemHelper::filter_int(500));
	}

	public function testReputation()
	{
		$this->assertInternalType('int', SteemHelper::reputation(3000000));
	}

	public function vestToSteem()
	{
		$this->assertInternalType('float', SteemHelper::vestToSteem('24477.640182 VESTS', '369735200112.175967 VESTS', '178661603.552 STEEM'));
	}

	public function testCharAt()
	{
		$this->assertEquals('a', SteemHelper::charAt('abcd', 0));
	}

}

?>