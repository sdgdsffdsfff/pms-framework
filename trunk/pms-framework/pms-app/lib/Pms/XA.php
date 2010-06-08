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
	 * @var Object
	 */
	public $msg = null;
	
	/**
	 * Get message object
	 * 
	 * @return Object
	 */
	public function getMsg ()
	{
		return $this->msg;
	}
	
	/**
	 * Start the transaction
	 * 
	 * @return void
	 */
	public function start ()
	{
		$this->msg = $this->recvMsg();
	}
	
	/**
	 * Prepare actions
	 * 
	 * @return void
	 */
	public function prepare ()
	{
		
	}
	
	/**
	 * Commit changes
	 * 
	 * @return void
	 */
	public function commit ()
	{
		
	}
	
	/**
	 * Rollback changes
	 * 
	 * @return void
	 */
	public function rollback ()
	{
		return $this->sendMsg($this->msg);
	}
}