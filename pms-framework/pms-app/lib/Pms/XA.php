<?php
/**
 * PMS Framework
 *
 * @category   Pms_XA
 * @package    Pms_XA
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */

require_once 'Pms/Client.php';

/**
 * @package Pms_XA
 */
class Pms_XA
{
	/**
	 * @var object
	 */
	public $msg = null;
	
	/**
	 * @var array
	 */
	public $buf = array();
	
	/**
	 * @var array
	 */
	public $ports = array();
	
	/**
	 * @var Pms_Client
	 */
	public $xports = array();
	
	/**
	 * Construct
	 */
	public function __construct ($ports)
	{
		// init ports array
		$this->ports = $ports ? (array) $ports : Pms_Util::getServerPorts(SERVER_PORT);
		
		if (!is_array($this->ports)) {
			require_once 'Pms/Message/Exception.php';
			throw new Pms_Message_Exception('server ports must be an array');
		}
		
		// init xports array ; for keeping geting msg from not empty client
		foreach ($this->ports as $port) {
			$client = new Pms_Client($port);
			if (!$client->getSize()) continue;
			$this->xports[] = $port;
		}
	}
	
	/**
	 * Get message object
	 * 
	 * @return Object
	 */
	public function getMsg ()
	{
		// all queues are empty
		if (!sizeof($this->xports)) return false;
		// get random client
		$client = new Pms_Client($this->xports);
		// if mq is empty
		if (!$client->getSize()) {
			$ports = Pms_Util::array_remove($this->xports, $client->getPort());
			return $this->getMsg();
		}
		// store message
		$this->msg = $client->recvMsg();
		// store in trans
		$this->buf[] = $this->msg;
		// return current msg
		return $this->msg;
	}
	
	/**
	 * Start the transaction
	 * 
	 * @return void
	 */
	public function start ()
	{
		$this->buf = array();
	}
	
	/**
	 * Prepare actions
	 * 
	 * @return void
	 */
	public function prepare ()
	{
		
	}
	
	/**
	 * Commit changes
	 * 
	 * @return void
	 */
	public function commit ()
	{
		$this->buf = array();
	}
	
	/**
	 * Rollback changes
	 * 
	 * @return void
	 */
	public function rollback ()
	{
		foreach ((array) $this->buf as $msg) {
			// get random client
			$client = new Pms_Client($this->ports);
			// send back msg for rollback
			$client->sendMsg($msg);
		}
	}
}