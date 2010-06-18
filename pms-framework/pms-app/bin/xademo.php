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

$action = $argv[1]; // passed parameter

$host	= PMS_SERVER_HOST;
$ports	= Pms_Util::getServerPorts(PMS_SERVER_PORT);

$xa = new Pms($host, $ports);

$xa->beginTransaction(); // start transaction

try {
	
	if ($action == 'get') {
		echo "Deal with the messages : \n";
		// deal with messages
		for ($i = 0; $i < 10; $i++) {
			var_dump($xa->get());
		}
	}
	
	if ($action == 'getback') {
		echo "Deal with the messages : \n";
		// deal with messages
		for ($i = 0; $i < 10; $i++) {
			var_dump($xa->get());
		}
		throw new Exception("Throw Rollback Exception");
	}

	if ($action == 'add') {
		echo "Send message : \n";
		// deal with messages
		require_once 'Pms/Message.php';
		$msg = new Pms_Message();
		$msg->setType(Pms_Message::MSG_LEVEL_1);
		$msg->setData("Message IN Queue : " . $xa->port());
		$msg = json_encode($msg); // json format data
		$xa->add($msg);
		$xa->debug();
	}
	
	if ($action == 'addback') {
		echo "Send message : \n";
		// deal with messages
		require_once 'Pms/Message.php';
		$msg = new Pms_Message();
		$msg->setType(Pms_Message::MSG_LEVEL_1);
		$msg->setData("Message IN Queue : " . $xa->port());
		$msg = json_encode($msg); // json format data
		$xa->add($msg);
		$xa->debug();
		throw new Exception("Throw Rollback Exception");
	}

	if (!$action || !in_array($action, array('get', 'getback', 'add', 'addback'))) {
		echo 
<<<USAGE

Usage: php xademo.php <ACTIONS>

Actions:
    get        :   Get 10 messages
    getback    :   Get 10 messages and rollback
    add        :   Add one message
    addback    :   Add one message and rollback

    If you do not input any action, we will deal with the message 
    You can check PMS MQ stats by command "php client.php stats"


USAGE;
	}
	else {
		$xa->commit(); // commit
	}

} catch (Exception $e) {
	Hush_Util::trace($e);
	$xa->rollback(); // rollback
	exit;
}
