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
 
// could be call by other scripts ; avoid path change
require_once realpath(dirname(__FILE__).'/../etc') . '/config.inc';

require_once 'Pms.php';

$action	= $argv[1]; // passed parameter

$host	= SERVER_HOST;
$ports	= Pms_Util::getServerPorts(SERVER_PORT);

try {
	
	$client = new Pms();
	$client->connect($host, $ports);
	
	// add messages
	if ($action == 'send') 
	{
		require_once 'Pms/Message.php';
		$msg = new Pms_Message();
		$msg->setType(Pms_Message::MSG_LEVEL_1);
		$msg->setData("Message IN Queue : " . $client->port());
		$msg = json_encode($msg); // json format data
		$client->add($msg);
		$client->debug();
		exit;
	}
	
	// fill messages
	if ($action == 'fill') 
	{
		for ($i = 0; $i < 30; $i++) {
			$client = new Pms($host, $ports);
			require_once 'Pms/Message.php';
			$msg = new Pms_Message();
			$msg->setType(Pms_Message::MSG_LEVEL_1);
			$msg->setData("Message IN Queue : " . $client->port());
			$msg = json_encode($msg); // json format data
			$client->add($msg);
			$client->debug();
		}
		exit;
	}
	
	// deal with messages
	if ($action == 'recv') 
	{
		// do get message
		var_dump($client->get());
		$client->debug();
		exit;
	}
	
	// show mq server stats
	if ($action == 'stats') 
	{
		echo $client->stats();
		exit;
	}
	
	// deal with messages
	if ($action == 'clear') 
	{
		$client->clear();
		exit;
	}

	// do all messages one by one
	if ($action == 'doall')
	{
		foreach ($ports as $port) {
			// find in all pms mqs
			$client = new Pms($host, $port);
			// do get all message
			while ($client->size()) {
				var_dump($client->get());
				$client->debug();
			}
		}
		exit;
	}
	
	echo 
<<<USAGE

Usage: php client.php <ACTIONS>

Actions:
    send    :   Send a message to PMS Server
    recv    :   Recv a message from PMS Server
    fill    :   Fill random messages to the queues
    doall   :   Recv all messages one by one
    stats   :   Get PMS Server queues stats 
    clear   :   Clear all message queues


USAGE;

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}