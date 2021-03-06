<?php
/**
 * PMS Framework
 *
 * @category   Pms
 * @package    Pms
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once 'Hush/Process.php';

require_once 'Pms/Util.php';

/**
 * @package Pms
 */
class Pms_Server extends Hush_Process
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
	 * Construct
	 */
	public function __construct ($host = '', $ports = array())
	{
		parent::__construct(); // init shared space
		
		// init host address
		$this->host = $host ? (string) $host : PMS_SERVER_HOST;
		
		// init shared ports array
		$this->ports = $ports ? (array) $ports : Pms_Util::getServerPorts(PMS_SERVER_PORT);
		
		// init max process for server
		$this->setMaxProcess(count($ports));
		
		// clear pid file first
		// do only once !!!
		$this->__pid(false);
		
		// store parent process pid
		// do only once !!!
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
			// store children process pid
			// do only once !!!
			$this->__pid(true);
		} else {
			echo "\n";
		}
		
		echo "Listening on : " . $this->port . "\n";
		
		try {
			
			// start socket message queue server
			$server = Pms_Adaptor::server(array(
				'host' => $this->host,
				'port' => $this->port
			));
			
			$server->daemon();
			
		} catch (Exception $e) {
			Hush_Util::trace($e);
			$server->close();
			$this->run();
		}
	}
}