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
require_once 'Pms/Server.php';

$ports = Pms_Util::getServerPorts(SERVER_PORT);

try {
	
	$daemon = new Pms_Server($ports);
	$daemon->start();

} catch (Exception $e) {
	Hush_Util::trace($e);
	exit;
}