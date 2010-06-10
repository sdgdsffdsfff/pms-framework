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
		
		// clear pid file first
		$this->__pid(false);
		
		// store parent process pid
		$this->__pid(true);
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
	 * Generate run pid file
	 * 
	 * @return void
	 */
	private function __pid ($set = false)
	{
		$pid_file = __DAT_DIR . '/pms.pid';
		// set current process id
		if ($set) {
			file_put_contents($pid_file, getmypid() . "\n", FILE_APPEND);
		// clear all process id
		}else {
			file_put_contents($pid_file, "");
		}
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
			
			// store children process pid
			$this->__pid(true);
			
			// start socket message queue server
			$server = new Pms_Message_Server(SERVER_HOST, $this->port);
			$server->daemon();
			
		} catch (Exception $e) {
			Hush_Util::trace($e);
			$server->close();
			$this->run();
		}
	}
}