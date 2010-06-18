<?php
/**
 * PMS Framework
 *
 * @category   Pms
 * @package    Pms
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */

require_once 'Pms/Util.php';

/**
 * @package Pms
 */
class Pms_Client
{
	/**
	 * @var string
	 */
	public $host = '';
	
	/**
	 * @var int
	 */
	public $port = 0;
	
	/**
	 * @var array
	 */
	public $ports = array();
	
	/**
	 * @var array
	 */
	public $xports = array();
	
	/**
	 * @var Pms_Client
	 */
	public $client = null;
	
	/**
	 * Construct
	 */
	public function __construct ($host = '', $ports = array())
	{
		// init host address
		$this->host = $host ? (string) $host : PMS_SERVER_HOST;
		
		// init ports array
		$this->ports = $ports ? (array) $ports : Pms_Util::getServerPorts(PMS_SERVER_PORT);
		
		if (!is_array($this->ports)) {
			require_once 'Pms/Message/Exception.php';
			throw new Pms_Message_Exception('server ports must be an array');
		}
		
		// init xports array ; for keeping geting msg from not empty client
		foreach ($this->ports as $port) {
			$client = Pms_Adaptor::client(array(
				'host' => $this->host,
				'port' => $port
			));
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
	 */
	private function __call ($method, $params)
	{
		$client = Pms_Adaptor::client(array(
			'host' => $this->host,
			'port' => $this->port
		));
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
		$client = new Pms_Client($this->host, $this->xports);
		
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
			$client = Pms_Adaptor::client(array(
				'host' => $this->host,
				'port' => $port
			));
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