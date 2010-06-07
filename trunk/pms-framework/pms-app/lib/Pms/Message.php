<?php
/**
 * PMS Framework
 *
 * @category   Pms_Message
 * @package    Pms_Message
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once 'Hush/Message.php';

/**
 * @package Pms_Message
 */
class Pms_Message extends Hush_Message
{
	/**
	 * Message types only for handler's testing
	 * All the constants will be overridden by your class
	 * @see Pms_Message_Handler
	 * @static
	 */
	const MSG_LEVEL_1	= 1;
	const MSG_LEVEL_2	= 2;
	const MSG_LEVEL_3	= 3;
}