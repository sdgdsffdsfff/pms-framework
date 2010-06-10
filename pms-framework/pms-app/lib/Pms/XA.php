<?php
/**
 * PMS Framework
 *
 * @category   Pms_XA
 * @package    Pms_XA
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */

require_once 'Pms/Client.php';

/**
 * @package Pms_XA
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
	public $buf = array();
	
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
		if ($this->msg) $this->buf[] = $this->msg;
		// return current msg
		return $this->msg;
	}
	
	/**
	 * Start the transaction
	 * 
	 * @return void
	 */
	public function start ()
	{
		$this->buf = array();
	}
	
	/**
	 * Commit changes
	 * 
	 * @return void
	 */
	public function commit ()
	{
		$this->buf = array();
	}
	
	/**
	 * Rollback changes
	 * 
	 * @return void
	 */
	public function rollback ()
	{
		foreach ((array) $this->buf as $msg) {
			// get random port number
			$this->__rand();
			// send back msg for rollback
			if ($msg) $this->sendMsg($msg);
		}
	}
}