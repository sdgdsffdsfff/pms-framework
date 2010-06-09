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
	 * @var array
	 */
	public $ports = array();
	
	/**
	 * @var int
	 */
	public $port = 0;
	
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
		
		// get random port number
		srand((float) microtime() * 10000000);
		$this->port = $this->ports[array_rand($this->ports)];
	}
	
	/**
	 * Magic method
	 */
	public function __call ($method, $params)
	{
		$client = new Pms_Message_Client(SERVER_HOST, $this->port);
		return call_user_method_array($method, $client, $params);
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
		echo "MQ Pid : " . $this->getPid() . " ; " . 
			 "MQ name : " . $this->getName() . " ; " .
			 "Messages Total : " . $this->getSize() . 
			 "\n";
	}
}