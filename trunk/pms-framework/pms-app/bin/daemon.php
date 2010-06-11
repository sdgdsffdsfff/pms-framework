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
require_once 'Pms/Server.php';

/*
 * Set exception default handler
 * Avoid uncaught exception to interrupt the server process !!!
 */
set_exception_handler(create_function('$e', 'return $e->getException();'));

$host	= SERVER_HOST;
$ports	= Pms_Util::getServerPorts(SERVER_PORT);

try {

	$daemon = new Pms_Server($host, $ports);
	$daemon->start();

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}