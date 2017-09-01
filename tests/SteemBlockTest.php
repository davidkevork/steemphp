<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemBlockTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->SteemBlock = new \SteemPHP\SteemBlock('https://steemd.steemit.com');
	}

	public function testGetApi()
	{
		$this->assertInternalType('int', $this->SteemBlock->getApi('login_api'));
	}

	public function testGetBlock()
	{
		$this->assertArrayHasKey('witness', $this->SteemBlock->getBlock(1));
	}

	public function testGetBlockHeader()
	{
		$this->assertArrayHasKey('witness', $this->SteemBlock->getBlockHeader(1));
	}

	public function testGetOpsInBlock()
	{
		$this->assertArrayHasKey('trx_id', $this->SteemBlock->getOpsInBlock(15082480)[0]);
	}

	public function testGetTransactionHex()
	{
		$this->assertArrayHasKey('00000000000000000000000000', $this->SteemBlock->getTransactionHex('22e7151e8dbf10c8342cf174db4d18788dbede28'));
	}

	public function testGetTransaction()
	{
		$this->assertArrayHasKey('ref_block_num', $this->SteemBlock->getTransaction('22e7151e8dbf10c8342cf174db4d18788dbede28'));
	}

	public function testGetKeyReferences()
	{
		$this->assertArrayHasKey('0', $this->SteemBlock->getKeyReferences('STM8TkTHDY5ayet6JHyktY9ghV8PwiZ8FSgKSGaSM9PHEdtvCdxBK'));
	}

}

?>