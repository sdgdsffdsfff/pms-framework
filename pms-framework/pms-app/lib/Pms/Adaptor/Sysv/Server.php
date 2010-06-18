<?php
/**
 * PMS Framework
 *
 * @category   Pms_Adaptor_Sysv
 * @package    Pms_Adaptor_Sysv
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once 'Hush/Socket/Server.php';

require_once 'Pms/Message/Handler.php';

/**
 * @package Pms_Adaptor_Sysv
 */
class Pms_Adaptor_Sysv_Server extends Hush_Socket_Server
{
	/**
	 * @var int
	 */
	public $port = 0;
	
	/**
	 * Construct
	 */
	public function __construct ($host = '', $port = null)
	{
		parent::__construct($host, $port);
		
		// use port as queue name
		$this->mq = Pms_Adaptor::queue(array(
			'name' => $port
		));
		
		$this->mq->addHandler(new Pms_Message_Handler());
		
		$this->port = $port;
	}
	
	/**
	 * Get current process id of the socket server
	 * Useful for multi-process server
	 * 
	 * @return int
	 */
	public function getPid ()
	{
		return getmypid();
	}
	
	/**
	 * Get current port number
	 * 
	 * @return string
	 */
	public function getPort ()
	{
		return $this->port;
	}
	
	/**
	 * Get current mq name
	 * 
	 * @return string
	 */
	public function getName ()
	{
		return $this->mq->getName();
	}
	
	/**
	 * Get mq size
	 * 
	 * @return int
	 */
	public function getSize ()
	{
		return $this->mq->size();
	}
	
	/**
	 * Get mq server stats
	 * Return all the mq storages' infomation
	 * 
	 * @return string
	 */
	public function getStats ()
	{
		ob_start();
		system("ipcs -q");
		$stat = ob_get_contents();
		ob_end_clean();
		return $stat;
	}
	
	/**
	 * Send message and return current mq name
	 * 
	 * @return string
	 */
	public function sendMsg ($msg)
	{
		return $this->mq->addMessage($msg)->getName();
	}
	
	/**
	 * Receive message one by one
	 * 
	 * @return bool
	 */
	public function recvMsg ()
	{
		return $this->mq->receive();
	}
	
	/**
	 * Clear current mq data
	 * 
	 * @return bool
	 */
	public function clearMq ()
	{
		return $this->mq->clear();
	}
}

