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

require_once 'Pms/Util.php';
require_once 'Pms/XA.php';

$action = $argv[1]; // passed parameter

$ports = Pms_Util::getServerPorts(SERVER_PORT);

$xa = new Pms_XA($ports);

$xa->start(); // start transaction

try {
	
	if (!$action) {
		echo "Deal with the messages : \n";
		// deal with messages
		for ($i = 0; $i < 10; $i++) {
			var_dump($xa->recv());
		}
	}
	
	if ($action == 'back') {
		echo "Deal with the messages : \n";
		// deal with messages
		for ($i = 0; $i < 10; $i++) {
			var_dump($xa->recv());
		}
		throw new Exception("Throw Rollback Exception");
	}

	if ($action) {
		echo 
<<<USAGE

Usage: php xademo.php <ACTIONS>

Actions:
    back    :   Do rollback demo

    If you do not input any action, we will deal with the message 
    You can check PMS MQ stats by command "php client.php stats"


USAGE;
	}

} catch (Exception $e) {
	
	Hush_Util::trace($e);
	$xa->rollback(); // rollback
	exit;
}

$xa->commit(); // commit
