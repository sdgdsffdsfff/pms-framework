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
		$this->ports = $ports ? $ports : Pms_Util::getServerPorts(SERVER_PORT);
		
		// get random port number
		$key = array_rand($this->ports);
		$this->port = $this->ports[$key];
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