<?php

include (__DIR__).'/../vendor/autoload.php';

class SteemArticleTest extends PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		$this->SteemArticle = new \SteemPHP\SteemArticle('https://steemd.steemit.com');
	}

	public function testGetApi()
	{
		$this->assertInternalType('int', $this->SteemArticle->getApi('login_api'));
	}

	public function testGetTrendingTags()
	{
		$this->assertArrayHasKey('name', $this->SteemArticle->getTrendingTags('steemit', 10)[0]);
	}

	public function testGetContent()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getContent('davidk', 'steemphp-new-functions-added-part-1'));
	}

	public function testGetcontentReplies()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getContentReplies('davidk', 'steemphp-new-functions-added-part-1')[0]);
	}

	public function testGetDiscussionsByTrending()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByTrending('life', 2)[0]);
	}

	public function testGetDiscussionsByCreated()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByCreated('life', 2)[0]);
	}

	public function testGetDiscussionsByActive()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByActive('life', 2)[0]);
	}

	public function testGetDiscussionsByPromoted()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByPromoted('life', 2)[0]);
	}

	public function testGetDiscussionsByCashout()
	{
		$this->assertInternalType('array', $this->SteemArticle->getDiscussionsByCashout('life', 2));
	}

	public function testGetDiscussionsByPayout()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByPayout('life', 2)[0]);
	}

	public function testGetDiscussionsByVotes()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByVotes('life', 2)[0]);
	}

	public function testGetDiscussionsByChildren()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByChildren('life', 2)[0]);
	}

	public function testGetDiscussionsByHot()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByHot('life', 2)[0]);
	}

	public function testGetDiscussionsByFeed()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByFeed('davidk', 2)[0]);
	}

	public function testGetDiscussionsByBlog()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByBlog('davidk', 2)[0]);
	}

	public function testGetDiscussionsByComments()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByComments('davidk', '', 2)[0]);
	}

	public function testGetDiscussionsByAuthorBeforeDate()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getDiscussionsByAuthorBeforeDate('davidk', '', '2017-06-29', 2)[0]);
	}

	public function testGetRepliesByLastUpvote()
	{
		$this->assertArrayHasKey('author', $this->SteemArticle->getRepliesByLastUpvote('davidk', '', 2)[0]);
	}

	public function testGet()
	{
		$this->assertArrayHasKey('voter', $this->SteemArticle->getActiveVotes('davidk', 'steemphp-new-functions-added-part-1')[0]);
	}

	public function testGetState()
	{
		$this->assertArrayHasKey('props', $this->SteemArticle->getState('/@davidk'));
	}

}

?>