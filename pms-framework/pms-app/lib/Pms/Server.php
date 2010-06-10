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
	 * @var int
	 */
	public $port = 0;
	
	/**
	 * Construct
	 */
	public function __construct ($ports = array())
	{
		parent::__construct(); // init shared space
		
		// init shared ports array
		$this->ports = $ports ? $ports : Pms_Util::getServerPorts(SERVER_PORT);
		
		// init max process for server
		$this->setMaxProcess(count($ports));
	}
	
	/**
	 * Process init logic
	 */
	public function __init ()
	{
		// release resource
		$this->__release();
		
		// for pms log
		echo "Start at : " . date("Y-m-d H:i:s") . "\n";
	}
	
	/**
	 * Process main logic
	 */
	public function run ()
	{
		// init first time
		if (!$this->port) {
			$ports = $this->ports;
			$this->port = array_pop($ports); // current process's port (no shared)
			$this->ports = $ports;
		} else {
			echo "\n";
		}
		
		echo "Listening on : " . $this->port . "\n";
		
		try {
			
			$server = new Pms_Message_Server(SERVER_HOST, $this->port);
			$server->daemon();
			
		} catch (Exception $e) {
			Hush_Util::trace($e);
			$server->close();
			$this->run();
		}
	}
}