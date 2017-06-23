<?php

include '../vendor/autoload.php';

use SteemPHP\SteemConnection;

$SteemConnection = new SteemConnection('https://steemd.steemit.com');

echo '<pre>';

/* getting account `davidk` details */
print_r($SteemConnection->getAccount('davidk'));

/* getting account `davidk` history. check src/SteemPHP/SteemConnection.php for more details */
print_r($SteemConnection->getAccountHistory('davidk'));

/* getting global properties */
print_r($SteemConnection->getProps());

/* getting the reputation of the account `davidk` */
print_r($SteemConnection->getReputation('davidk'));

/* getting how much is the accounts `davidk` vest is worth */
print_r($SteemConnection->vestToSteemByAccount('davidk'));

/* for developement only: get the api number */
print_r($SteemConnection->getApi('database_api'));

/* getting the list of users the account `davidk` is following */
print_r($SteemConnection->getFollowing('davidk'));

/* getting the list of users following the account `davidk` */
print_r($SteemConnection->getFollowers('davidk'));

/* getting the number of followers and follows to the account `davidk` */
print_r($SteemConnection->countFollows('davidk'));

/* getting the list of trending tags after the tag `steemit` and limited to 100 */
print_r($SteemConnection->getTrendingTags('steemit', 100));

/* getting the post content */
print_r($SteemConnection->getContent('davidk', 'new-steemit-client-api-in-php-is-under-development'));

/* getting all replies on the post content */
print_r($SteemConnection->getContentReplies('davidk', 'new-steemit-client-api-in-php-is-under-development'));

/* get list of tags used by the author `davidk`. */
/* this functions returns empty array will be removed in the future r its not fixed */
print_r($SteemConnection->getTagsUsedByAuthor('davidk'));

?>