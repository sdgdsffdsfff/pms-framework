<?php
/**
 * PMS Framework
 *
 * @category   Pms_Message
 * @package    Pms_Message
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once 'Hush/Message/Handler.php';

require_once 'Pms/Message.php';

/**
 * @package Pms_Message
 */
class Pms_Message_Handler extends Hush_Message_Handler
{
	/**
	 * Handler logic for deal with the messages
	 * Do specific function by different message types 
	 * Called by sendMsg() method for client
	 * 
	 * @see Pms_Message
	 * @return void
	 */
	public function doSend ()
	{
		// Get message from queue
		$msg = $this->getMessage();
		
		// Do action by message type
		switch ($msg->getType()) {
			case Pms_Message::MSG_LEVEL_1 : 
				echo "[Server doSend] LEVEL : 1 ; DATA : " . $msg->getData() . "\n";
				break;
			case Pms_Message::MSG_LEVEL_2 : 
				echo "[Server doSend] LEVEL : 2 ; DATA : " . $msg->getData() . "\n";
				break;
			case Pms_Message::MSG_LEVEL_3 : 
				echo "[Server doSend] LEVEL : 3 ; DATA : " . $msg->getData() . "\n";
				break;
			default : 
				echo "[Server doSend] Unknown Message\n";
				break;
		}
	}
	
	/**
	 * Handler logic for deal with the messages
	 * Do specific function by different message types 
	 * Called by recvMsg() method for client
	 * 
	 * @see Pms_Message
	 * @return void
	 */
	public function doRecv ()
	{
		// Get message from queue
		$msg = $this->getMessage();
		
		// Do action by message type
		switch ($msg->getType()) {
			case Pms_Message::MSG_LEVEL_1 : 
				echo "[Server doRecv] LEVEL : 1 ; DATA : " . $msg->getData() . "\n";
				break;
			case Pms_Message::MSG_LEVEL_2 : 
				echo "[Server doRecv] LEVEL : 2 ; DATA : " . $msg->getData() . "\n";
				break;
			case Pms_Message::MSG_LEVEL_3 : 
				echo "[Server doRecv] LEVEL : 3 ; DATA : " . $msg->getData() . "\n";
				break;
			default : 
				echo "[Server doRecv] Unknown Message\n";
				break;
		}
	}
}