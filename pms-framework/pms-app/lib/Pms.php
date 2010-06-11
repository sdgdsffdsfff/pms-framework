<?php
/**
 * PMS Framework
 *
 * @category   Pms
 * @package    Pms
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once 'Pms/XA.php';
require_once 'Pms/Util.php';
require_once 'Pms/Exception.php';

/**
 * @package Pms
 */
class Pms
{
	/**
	 * @var Pms_XA
	 */
	public $xa_client = null;
	
	/**
	 * @var bool
	 */
	private $xa_start = false;
	
	/**
	 * Construct
	 */
	public function __construct ($host = '', $ports = array())
	{
		if ($host || $ports) {
			$this->connect($host, $ports);
		}
	}
	
	/**
	 * Destruct
	 */
	public function __destruct ()
	{
		$this->close();
	}
	
	/**
	 * Open a socket connection to PMS host
	 * 
	 * @param string $host PMS host name or ip address
	 * @param array	$ports PMS host ports array
	 * @return bool
	 */
	public function connect ($host, $ports)
	{
		if (!$this->xa_client) {
			if (!$host || !$ports) {
				throw new Pms_Exception('Please specify the PMS server host and ports array');
				return false;
			}
			$this->xa_client = new Pms_XA($host, $ports);
		}
		return true;
	}
	
	/**
	 * Close current connection to PMS host
	 * 
	 * @return void
	 */
	public function close ()
	{
		$this->xa_client->close();
	}
	
	/**
	 * Add a message to PMS server
	 * 
	 * @param string $msg Json encode string (must contain 'type' and 'data' fields)
	 * @return void
	 */
	public function add ($msg)
	{
		if (!Pms_Util::is_json($msg)) {
			throw new Pms_Exception('Please add a json format message');
		}
		// send directly
		if (!$this->xa_start) {
			$this->xa_client->sendMsg($msg);
		// in transaction
		} else {
			$this->xa_client->send($msg);
		}
	}
	
	/**
	 * Get message from PMS server
	 * 
	 * @return object
	 */
	public function get ()
	{
		// send directly
		if (!$this->xa_start) {
			return $this->xa_client->getMsg();
		// in transaction
		} else {
			return $this->xa_client->recv();
		}
	}
	
	/**
	 * Get message queues total size
	 * 
	 * @return int
	 */
	public function size ()
	{
		return $this->xa_client->getSize();
	}
	
	/**
	 * Get current port number
	 * 
	 * @return int
	 */
	public function port ()
	{
		return $this->xa_client->getPort();
	}
	
	/**
	 * Get message queues stats infomation
	 * 
	 * @return string
	 */
	public function stats ()
	{
		return $this->xa_client->getStats();
	}
	
	/**
	 * Clear all message queues
	 * Please be careful to this method !!!
	 * 
	 * @return void
	 */
	public function clear ()
	{
		$this->xa_client->clearAll();
	}
	
	/**
	 * Print debug message
	 * 
	 * @return void
	 */
	public function debug ()
	{
		$this->xa_client->debugMsg();
	}
	
	/**
	 * Begin a transaction for get & set messages
	 * 
	 * @return void
	 */
	public function beginTransaction ()
	{
		$this->xa_client->start();
		$this->xa_start = true;
	}
	
	/**
	 * Commit changes in transaction
	 * 
	 * @return void
	 */
	public function commit ()
	{
		$this->xa_client->commit();
		$this->xa_start = false;
	}
	
	/**
	 * Rollback changes in transaction
	 * 
	 * @return void
	 */
	public function rollback ()
	{
		$this->xa_client->rollback();
		$this->xa_start = false;
	}
}
