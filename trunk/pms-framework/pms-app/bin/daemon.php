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

$ports = Pms_Util::getServerPorts(SERVER_PORT);

$daemon = new Pms_Server($ports);
$daemon->start();