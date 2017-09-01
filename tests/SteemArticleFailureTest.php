<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemArticleFailureTest extends PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		$this->SteemArticle = new \SteemPHP\SteemArticle('https://rpc.google.com/');
	}

	public function testGetApi()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getApi('login_api'));
	}

	public function testGetTrendingTags()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getTrendingTags('steemit', 10));
	}

	public function testGetContent()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getContent('davidk', 'steemphp-new-functions-added-part-1'));
	}

	public function testGetcontentReplies()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getContentReplies('davidk', 'steemphp-new-functions-added-part-1'));
	}

	public function testGetDiscussionsByTrending()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByTrending('life', 2));
	}

	public function testGetDiscussionsByCreated()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByCreated('life', 2));
	}

	public function testGetDiscussionsByActive()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByActive('life', 2));
	}

	public function testGetDiscussionsByPromoted()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByPromoted('life', 2));
	}

	public function testGetDiscussionsByCashout()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByCashout('life', 2));
	}

	public function testGetDiscussionsByPayout()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByPayout('life', 2));
	}

	public function testGetDiscussionsByVotes()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByVotes('life', 2));
	}

	public function testGetDiscussionsByChildren()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByChildren('life', 2));
	}

	public function testGetDiscussionsByHot()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByHot('life', 2));
	}

	public function testGetDiscussionsByFeed()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByFeed('davidk', 2));
	}

	public function testGetDiscussionsByBlog()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByBlog('davidk', 2));
	}

	public function testGetDiscussionsByComments()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByComments('davidk', '', 2));
	}

	public function testGetDiscussionsByAuthorBeforeDate()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getDiscussionsByAuthorBeforeDate('davidk', '', '2017-06-29', 2));
	}

	public function testGetRepliesByLastUpvote()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getRepliesByLastUpvote('davidk', '', 2));
	}

	public function testGet()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getActiveVotes('davidk', 'steemphp-new-functions-added-part-1'));
	}

	public function testGetState()
	{
		$this->assertArrayHasKey('instance', $this->SteemArticle->getState('/@davidk'));
	}

}

?>