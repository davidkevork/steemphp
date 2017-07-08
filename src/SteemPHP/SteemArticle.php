<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;
use SteemPHP\SteemHelper;

/**
* SteemConnection
* 
* All the Calls will be called from this class as it will have most of the functions
*/
class SteemArticle
{

/**
	 * @var $host
	 * 
	 * $host will be where our script will connect to fetch the data
	 */
	protected $host;

	/**
	 * @var $client
	 * 
	 * $client is part of JsonRPC which will be used to connect to the server
	 */
	protected $client;

	/**
	 * Initialize the connection to the host
	 * @param String $host
	 * 
	 * $host = ['https://node.steem.ws', 'https://steemd.steemit.com']
	 */
	public function __construct($host = 'https://steemd.steemit.com')
	{
		$this->host = trim($host);
		$this->httpClient = new HttpClient($this->host);
		$this->httpClient->withoutSslVerification();
		$this->client = new Client($this->host, false, $this->httpClient);
	}

	/**
	 * Get Api number
	 * @param String $name 
	 * @return int
	 */
	public function getApi($name)
	{
		return $this->client->call(1, 'get_api_by_name', [$name]);
	}

	/**
	 * Get the list of trending tags after $afteTag
	 * @param String $afterTag 
	 * @param int $limit 
	 * @return array
	 */
	public function getTrendingTags($afterTag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_trending_tags', [$afterTag, SteemHelper::filterInt($limit)]);
	}

	/**
	 * Get Content of an article
	 * @param String $author 
	 * @param String $permlink 
	 * @return array
	 */
	public function getContent($author, $permlink)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_content', [$author, $permlink]);
	}

	/**
	 * Get Replies on an article
	 * @param String $author 
	 * @param String $permlink 
	 * @return array
	 */
	public function getContentReplies($author, $permlink)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_content_replies', [$author, $permlink]);
	}

	/**
	 * Get list of articles (content/votes/comments) using the tag $tag
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByTrending($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_trending', [$query]);
	}

	/**
	 * Get list of new articles created using the tag $tag
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByCreated($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_created', [$query]);
	}


	/**
	 * Get list of active article for the tag $tag
	 * active article: an article that has just recieved an upvote/comment/reblog
	 * @param Strign $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByActive($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_active', [$query]);
	}

	/**
	 * Get list of articles which are promoted and use the tag $tag
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByPromoted($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_promoted', [$query]);	
	}

	/**
	 * Get list of articles were the rewards will be payed in less than 12 hour
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByCashout($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_cashout', [$query]);	
	}

	/**
	 * Get list of articles with the highest payout using the tag $tag
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByPayout($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_payout', [$query]);
	}

	/**
	 * Get list of articles that has recieved the highest upvotes using the tag $tag
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByVotes($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_votes', [$query]);
	}

	/**
	 * Get articles by childer
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByChildren($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_children', [$query]);
	}

	/**
	 * Get list of articles which are hot and using tha tag $tag
	 * @param String $tag 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByHot($tag, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $tag, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_hot', [$query]);
	}

	/**
	 * Get list of articles in the feed section for the author $author
	 * @param String $author 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByFeed($author, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $author, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_feed', [$query]);
	}

	/**
	 * Get list of articles written/reblogged by the author $author
	 * @param String $author 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByBlog($author, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['tag' => $author, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_blog', [$query]);
	}

	/**
	 * Get list of articles the author #author has commented on
	 * @param String $author 
	 * @param int $limit 
	 * @return type
	 */
	public function getDiscussionsByComments($author, $permlink, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		$query = ['start_author' => $author, 'start_permlink' => $permlink, 'limit' => SteemHelper::filterInt($limit)];
		return $this->client->call($this->api, 'get_discussions_by_comments', [$query]);	
	}

	/**
	 * Get the list of articles written by the author $author before the date $beforeDate
	 * @param String $author 
	 * @param String $startPermlink 
	 * @param datetime and timestamp $beforeDate 
	 * @param int $limit 
	 * @return array
	 */
	public function getDiscussionsByAuthorBeforeDate($author, $startPermlink, $beforeDate, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_discussions_by_author_before_date', [$author, $startPermlink, SteemHelper::filterDate($beforeDate), SteemHelper::filterInt($limit)]);	
	}

	/**
	 * Get list of replies for where the article has recieved the most upvotes for the author $author
	 * where the article has been posted less than a week ago 
	 * @param String $startAuthor 
	 * @param String $startPermlink 
	 * @param int $limit 
	 * @return array
	 */
	public function getRepliesByLastUpvote($startAuthor, $startPermlink, $limit = 100)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_replies_by_last_update', [$startAuthor, $startPermlink, SteemHelper::filterInt($limit)]);	
	}

	/**
	 * Get the list of upvotes the article $startPermlink has received
	 * @param String $startAuthor 
	 * @param String $startPermlink 
	 * @param int $limit 
	 * @return array
	 */
	public function getActiveVotes($startAuthor, $startPermlink)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_active_votes', [$startAuthor, $startPermlink]);
	}

	/**
	 * Get state for $path ex: /@davidk
	 * @param String $path 
	 * @return array
	 */
	public function getState($path)
	{
		$this->api = $this->getApi('database_api');
		return $this->client->call($this->api, 'get_state', [$path]);
	}

}

?>
