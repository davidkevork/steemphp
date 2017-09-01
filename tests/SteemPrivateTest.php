<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemPrivateTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemPrivate = new \SteemPHP\SteemPrivate;
	}

	public function testSetPrefix()
	{
		$this->SteemPrivate->setPrefix('STX');
		$this->assertEquals('STX', $this->SteemPrivate->prefix);
	}

	public function testToWif()
	{
		$this->assertEquals('5JpHaesrxhiaBsqNxWFAWkkykVBPTqs1KdiRLhcBbpLjiVDRypg', $this->SteemPrivate->toWif('guest123', '5JRaypasxMx1L97ZUX7YuC5Psb5EAbF821kkAGtBj7xCJFQcbLg', 'owner'));
	}

	public function testFromSeed()
	{
		$this->assertEquals('5JN8BCwLUw3CddU9tGjyWX7ahV9iye1tfsDzn9Ab5tg6kzd1fZ8', $this->SteemPrivate->fromSeed('guest1235JRaypasxMx1L97ZUX7YuC5Psb5EAbF821kkAGtBj7xCJFQcbLgowner'));
	}

	public function testFromBuffer()
	{
		$this->assertEquals('5JN8BCwLUw3CddU9tGjyWX7ahV9iye1tfsDzn9Ab5tg6kzd1fZ8', $this->SteemPrivate->fromBuffer('484aaef3bc4f8983a6ae44526f39f949b7cdd731db7d6f87a93b1432b3dde468'));
	}

	public function testIsWif()
	{
		$this->assertTrue($this->SteemPrivate->isWif('5JRaypasxMx1L97ZUX7YuC5Psb5EAbF821kkAGtBj7xCJFQcbLg'));
	}

	public function testFromWif()
	{
		$this->assertEquals('5JRaypasxMx1L97ZUX7YuC5Psb5EAbF821kkAGtBj7xCJFQcbLg', $this->SteemPrivate->fromWif('5JRaypasxMx1L97ZUX7YuC5Psb5EAbF821kkAGtBj7xCJFQcbLg'));
	}

}

?>