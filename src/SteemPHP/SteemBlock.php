<?php

namespace SteemPHP;

use JsonRPC\Client;
use JsonRPC\HttpClient;
use SteemPHP\SteemHelper;

/**
* SteemBlock
* 
* SteemBlock contains Steemit Block functions
* 
* This Class is still under development and not all functions are ready to be used
*/
class SteemBlock
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
	 * 
	 * @param      string  $host   The node you want to connect
	 */
	public function __construct($host = 'https://steemd.steemit.com')
	{
		$this->host = trim($host);
		$this->httpClient = new HttpClient($this->host);
		$this->httpClient->withoutSslVerification();
		$this->client = new Client($this->host, false, $this->httpClient);
	}

	/**
	 * Gets the api number by api $name
	 *
	 * @param      string  $name   The name of the api
	 *
	 * @return     integer         The api number
	 */
	public function getApi($name)
	{
		try {
			return $this->client->call(1, 'get_api_by_name', [$name]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Block by block number
	 * @param int $blockNumber
	 * @return array
	 */
	public function getBlock($blockNumber)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_block', [SteemHelper::filterInt($blockNumber)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Block header by block number
	 * @param int $blockNumber
	 * @return array
	 */
	public function getBlockHeader($blockNumber)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_block_header', [SteemHelper::filterInt($blockNumber)]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Block header by block number
	 * @param int $blockNumber
	 * @param boolean $onlyVirtual
	 * @return array
	 */
	public function getOpsInBlock($blockNumber, $onlyVirtual = false)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_ops_in_block', [SteemHelper::filterInt($blockNumber), $onlyVirtual]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get Transaction Hex from a transaction
	 * std::string get_transaction_hex(const signed_transaction& trx)const;
	 * @param string $trx 
	 * @return array
	 */
	public function getTransactionHex($trx)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_transaction_hex', [$trx]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * Get transaction by transaction id
	 * annotated_signed_transaction database_api::get_transaction( transaction_id_type id )const
	 * @param string $trx_id 
	 * @return array
	 */
	public function getTransaction($trx_id)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_transaction', [$trx_id]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * set<public_key_type> get_required_signatures( const signed_transaction& trx, const flat_set<public_key_type>& available_keys )const;
	 * @param signed_transaction $trx
	 * @param flat_set<public_key_type> $availableKeys
	 * @return array
	 */
	public function getRequiredSignatures($trx, $availableKeys)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_required_signatures', [$trx, $availableKeys]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * set<public_key_type> get_potential_signatures( const signed_transaction& trx )const;
	 * @param signed_transaction $trx
	 * @return array
	 */
	public function getPotentialSignatures($trx)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'get_potential_signatures', [$trx]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * bool verify_authority( const signed_transaction& trx )const;
	 * @param signed_transaction $trx 
	 * @return array on failure - boolean if successful
	 */
	public function verifyAuthority($trx)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'verify_authority', [$trx]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * bool verify_account_authority( const string& name_or_id, const flat_set<public_key_type>& signers )const;
	 * @param string $NameOrId 
	 * @param flat_set<public_key_type> $singers 
	 * @return array on failure - boolean if successful
	 */
	public function verifyAccountAuthority($NameOrId, $singers)
	{
		try {
			$this->api = $this->getApi('account_by_key_api');
			return $this->client->call($this->api, 'verify_account_authority', [$NameOrId, $singers]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * vector<set<string>> get_key_references( vector<public_key_type> key )const;
	 * @param vector<public_key_type> $key 
	 * @return array
	 */
	public function getKeyReferences($key)
	{
		if (!is_array($key)) {
			$this->key[] = $key;
		} else {
			$this->key = $key;
		}
		try {
			$this->api = $this->getApi('account_by_key_api');
			return $this->client->call($this->api, 'get_key_references', [$this->key]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * @brief Broadcast a transaction to the network
	 * @param trx The transaction to broadcast
	 *
	 * The transaction will be checked for validity in the local database prior to broadcasting. If it fails to
	 * apply locally, an error will be thrown and the transaction will not be broadcast.
	 *
	 * void broadcast_transaction(const signed_transaction& trx);
	 * @param string $trx
	 * @return array
	 */
	public function broadcastTransaction($trx)
	{
		try {
			$this->api = $this->getApi('network_broadcast_api');
			return $this->client->call($this->api, 'broadcast_transaction', [$trx]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * @brief Broadcast a transaction to the network
	 * @param trx The transaction to broadcast
	 *
	 * The transaction will be checked for validity in the local database prior to broadcasting. If it fails to
	 * apply locally, an error will be thrown and the transaction will not be broadcast.
	 *
	 * void broadcast_transaction(const signed_transaction& trx);
	 * @param string $trx
	 * @return array
	 */
	public function broadcastTransactionSynchronous($trx)
	{
		try {
			$this->api = $this->getApi('network_broadcast_api');
			return $this->client->call($this->api, 'broadcast_transaction_synchronous', [$trx]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * void broadcast_block( const signed_block& block );
	 * @param array $block
	 * @return array
	 * @failure array['instance'] = 'JsonRPC\Exception\ResponseException';
	 */
	public function broadcastBlock($block)
	{
		try {
			$this->api = $this->getApi('network_broadcast_api');
			return $this->client->call($this->api, 'broadcast_block', [$block]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	/**
	 * void broadcast_block( const signed_block& block );
	 * @param array $block
	 * @return array
	 */
	public function broadcastTransactionWithCallback($confirmationCallback, $trx)
	{
		try {
			$this->api = $this->getApi('network_broadcast_api');
			return $this->client->call($this->api, 'broadcast_transaction_with_callback', [$confirmationCallback, $trx]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	// To be documented
	public function setMaxBlockAge($maxBlcokAge)
	{
		try {
			$this->api = $this->getApi('network_broadcast_api');
			return $this->client->call($this->api, 'set_max_block_age', [$maxBlcokAge]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	// To be documented
	public function setSubscribeCallback($callBack, $clearFilter)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'set_subscribe_callback', [$callBack, $clearFilter]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	// To be documented	
	public function setPendingTransactionCallback($cb)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'set_pending_transaction_callback', [$cb]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	// To be documented
	public function setBlockAppliedCallback($cb)
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'set_block_applied_callback', [$cb]);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

	// To be documented
	public function cancelAllSubscriptions()
	{
		try {
			$this->api = $this->getApi('database_api');
			return $this->client->call($this->api, 'cancel_all_subscriptions', []);
		} catch (\Exception $e) {
			return SteemHelper::handleError($e);
		}
	}

}

?>