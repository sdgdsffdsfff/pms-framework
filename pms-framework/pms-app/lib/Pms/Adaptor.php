<?php
/**
 * PMS Framework
 *
 * @category   Pms
 * @package    Pms
 * @author     James.Huang <shagoo@gmail.com>
 * @version    $Id$
 */
 
require_once 'Pms/Adaptor/Exception.php';

class Pms_Adaptor
{
	public static $adaptor = null;
	
	public static $client = null;
	
	public static $server = null;
	
	public static $queue = null;
	
	public static function factory ($adaptor)
	{
		self::$adaptor = $adaptor;
	}
	
	public static function client ($config = array())
	{
		if (!self::$client) {
			$className = 'Pms_Adaptor_' . ucfirst(self::$adaptor) . '_Client';
			@require_once 'Pms/Adaptor/' . ucfirst(self::$adaptor) . '/Client.php';
			if (!class_exists($className)) {
				throw new Pms_Adaptor_Exception('Class ' . $className . 'does not exists');
			}
			$host = isset($config['host']) ? $config['host'] : '';
			$port = isset($config['port']) ? $config['port'] : '';
			return new $className($host, $port);
		}
		return self::$client;
	}
	
	public static function server ($config = array())
	{
		if (!self::$server) {
			$className = 'Pms_Adaptor_' . ucfirst(self::$adaptor) . '_Server';
			@require_once 'Pms/Adaptor/' . ucfirst(self::$adaptor) . '/Server.php';
			if (!class_exists($className)) {
				throw new Pms_Adaptor_Exception('Class ' . $className . 'does not exists');
			}
			$host = isset($config['host']) ? $config['host'] : '';
			$port = isset($config['port']) ? $config['port'] : '';
			return new $className($host, $port);
		}
		return self::$server;
	}
	
	public static function queue ($config = array())
	{
		if (!self::$queue) {
			$className = 'Pms_Adaptor_' . ucfirst(self::$adaptor) . '_Queue';
			@require_once 'Pms/Adaptor/' . ucfirst(self::$adaptor) . '/Queue.php';
			if (!class_exists($className)) {
				throw new Pms_Adaptor_Exception('Class ' . $className . 'does not exists');
			}
			$name = isset($config['name']) ? $config['name'] : '';
			return new $className($name);
		}
		return self::$queue;
	}
}