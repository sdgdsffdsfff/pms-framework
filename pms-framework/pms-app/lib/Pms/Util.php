<?php
/**
 * PMS Framework
 *
 * @category   Pms_Util
 * @package    Pms_Util
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */

require_once 'Hush/Util.php';

/**
 * @package Pms_Util
 */
class Pms_Util extends Hush_Util
{
	/**
	 * Parse server ports array from config.inc
	 * Format like : 11111,11112 or 11111-11112
	 * 
	 * @return array
	 */
	public static function getServerPorts ($port)
	{
		// format like 11111,11112
		if (preg_match('/\d+?,\d+?/i', $port)) {
			$ports = explode(',', $port);
		}
		
		// format like 11111-11112
		if (preg_match('/\d+?-\d+?/i', $port)) {
			$ports = explode('-', $port);
			$ports = range($ports[0], $ports[1]);
		}
		
		// check ports' format
		foreach ((array) $ports as $port) {
			if (!is_numeric($port)) {
				die("Server port format error");
			}
		}
		
		return $ports;
	}
}