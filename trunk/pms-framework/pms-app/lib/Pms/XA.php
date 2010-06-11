<?php
/**
 * PMS Framework
 *
 * @category   Pms
 * @package    Pms
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */

require_once 'Pms/Client.php';

/**
 * @package Pms
 */
class Pms_XA extends Pms_Client
{
	/**
	 * @var object
	 */
	public $msg = null;
	
	/**
	 * @var array
	 */
	public $recv_buf = array();
	
	/**
	 * @var array
	 */
	public $send_buf = array();
	
	/**
	 * Get message object
	 * 
	 * @return Object
	 */
	public function recv ()
	{
		// store message
		$this->msg = $this->getMsg();
		// store in trans
		if ($this->msg) $this->recv_buf[] = $this->msg;
		// return current msg
		return $this->msg;
	}
	
	/**
	 * Send message object
	 * 
	 * @return void
	 */
	public function send ($msg)
	{
		// store in trans
		if ($msg) $this->send_buf[] = $msg;
	}
	
	/**
	 * Start the transaction
	 * 
	 * @return void
	 */
	public function start ()
	{
		$this->recv_buf = array();
		$this->send_buf = array();
	}
	
	/**
	 * Commit changes
	 * 
	 * @return void
	 */
	public function commit ()
	{
		$this->recv_buf = array();
		
		foreach ((array) $this->send_buf as $msg) {
			// get random port number
			$this->__rand();
			// send back msg for rollback
			if ($msg) $this->sendMsg($msg);
		}
	}
	
	/**
	 * Rollback changes
	 * 
	 * @return void
	 */
	public function rollback ()
	{
		$this->send_buf = array();
		
		foreach ((array) $this->recv_buf as $msg) {
			// get random port number
			$this->__rand();
			// send back msg for rollback
			if ($msg) $this->sendMsg($msg);
		}
	}
}