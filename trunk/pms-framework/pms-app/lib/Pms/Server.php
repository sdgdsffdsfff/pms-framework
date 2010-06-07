<?php
/**
 * PMS Framework
 *
 * @category   Pms_Server
 * @package    Pms_Server
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once 'Hush/Process.php';

require_once 'Pms/Util.php';
require_once 'Pms/Message/Server.php';

class Pms_Server extends Hush_Process
{
	/**
	 * Construct
	 */
	public function __construct ($ports = array())
	{
		parent::__construct(); // init shared space
		
		// init ports array
		$this->ports = $ports ? $ports : Pms_Util::getServerPorts(SERVER_PORT);
	}
	
	public function __init ()
	{
		$this->__release();
	}
	
	public function run ()
	{
		$ports = $this->ports;
		$port = array_pop($ports);
		$this->ports = $ports;
		
		echo "Listening on : " . $port . "\n";
		
		$server = new Pms_Message_Server(SERVER_HOST, $port);
//		$server->debugMode(1);
		$server->daemon();
	}
}