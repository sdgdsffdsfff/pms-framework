<?php
/**
 * PMS Framework
 *
 * @category   Pms_Client
 * @package    Pms_Client
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */

require_once 'Pms/Util.php';
require_once 'Pms/Message/Client.php';

/**
 * @package Pms_Client
 */
class Pms_Client
{	
	/**
	 * @var int
	 */
	public $port = 0;
	
	/**
	 * @var array
	 */
	public $ports = array();
	
	/**
	 * @var Pms_Message_Client
	 */
	public $xports = array();
	
	/**
	 * @var Pms_Client
	 */
	public $client = null;
	
	/**
	 * Construct
	 */
	public function __construct ($ports = array())
	{
		// init ports array
		$this->ports = $ports ? (array) $ports : Pms_Util::getServerPorts(SERVER_PORT);
		
		if (!is_array($this->ports)) {
			require_once 'Pms/Message/Exception.php';
			throw new Pms_Message_Exception('server ports must be an array');
		}
		
		// init xports array ; for keeping geting msg from not empty client
		foreach ($this->ports as $port) {
			$client = new Pms_Message_Client(SERVER_HOST, $port);
			if (!$client->getSize()) continue;
			$this->xports[] = $port;
		}
		
		// get random port number
		$this->__rand();
	}
	
	/**
	 * Get random port number
	 * @return void
	 */
	protected function __rand ()
	{
		// get random port number
		srand((float) microtime() * 10000000);
		$this->port = $this->ports[array_rand($this->ports)];
	}
	
	/**
	 * Magic method
	 * @see Pms_Message_Server
	 */
	private function __call ($method, $params)
	{
		$client = new Pms_Message_Client(SERVER_HOST, $this->port);
		return call_user_method_array($method, $client, $params);
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
			return $client->getMsg();
		}
		
		// store current client
		$this->client = $client;
		
		// call mq server to recv msg
		return $client->recvMsg();
	}
	
	/**
	 * Get current port number
	 * 
	 * @return int
	 */
	public function getPort ()
	{
		return $this->port;
	}
	
	/**
	 * Clear all queue on the server
	 * Please be careful to this action !!!
	 * 
	 * @return void
	 */
	public function clearAll ()
	{
		foreach ($this->ports as $port) {
			$client = new Pms_Message_Client(SERVER_HOST, $port);
			$client->clearMq();
		}
	}
	
	/**
	 * Only for debuging
	 * 
	 * @return string 
	 */
	public function debugMsg ()
	{
		$client = $this->client ? $this->client : $this;
		
		echo "[Client Debug] " .
			 "MQ Pid : " . $client->getPid() . " ; " . 
			 "MQ name : " . $client->getName() . " ; " .
			 "Messages Total : " . $client->getSize() . 
			 "\n";
	}
}