<?php
/**
 * PMS Framework
 *
 * @ignore
 * @category   Pms_Message
 * @package    Pms_Message
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once '../etc/config.inc';

require_once 'Pms/Util.php';
require_once 'Pms/Client.php';

$action = $argv[1]; // passed parameter

$ports = Pms_Util::getServerPorts(SERVER_PORT);

try {
	
	$client = new Pms_Client($ports);
	
	// add messages
	if ($action == 'send') 
	{
		require_once 'Pms/Message.php';
		$msg = new Pms_Message();
		$msg->setType(Pms_Message::MSG_LEVEL_1);
		$msg->setData("Message IN Queue : " . $client->port);
		
		$client->sendMsg($msg);
		$client->debugMsg();
	}
	
	// deal with messages
	if ($action == 'recv') 
	{
		$client->recvMsg();
		$client->debugMsg();
	}
	
	// deal with messages
	if ($action == 'clear') 
	{
		$client->clearAll();
	}

	// show mq server stats
	if ($action == 'stats') 
	{
		echo $client->getStat();
	}

	// do all messages one by one
	if ($action == 'doall')
	{
		while (sizeof($ports) > 0) {
			// get random client
			$client = new Pms_Client($ports);
			// if mq is empty
			if (!$client->getSize()) {
				$ports = Pms_Util::array_remove($ports, $client->getPort());
				continue;
			}
			// do message
			$client->recvMsg();
			$client->debugMsg();
			// sleep for test
//			usleep(500000);
		}
	}

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}