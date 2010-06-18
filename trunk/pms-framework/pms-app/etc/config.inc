<?php

/**
 * Common Directories
 */
define('__ETC', dirname(__FILE__));
define('__ROOT', realpath(__ETC . '/../'));
define('__LIB_DIR', realpath(__ROOT . '/lib'));
define('__BIN_DIR', realpath(__ROOT . '/bin'));
define('__DAT_DIR', realpath(__ROOT . '/dat'));
define('__CACHE_DIR', realpath(__DAT_DIR . '/cache'));

/**
 * Common & Hush libraries paths
 * TODO : should be changed by enviornment change !!!
 */
define('__COMM_LIB_DIR', realpath(__ROOT . '/../phplibs'));

/**
 * Hush libraries paths
 * TODO : should be changed by enviornment change !!!
 */
define('__HUSH_LIB_DIR', realpath(__ROOT . '/../pms-lib'));

// initialize the include path env
set_include_path('.' . PATH_SEPARATOR . __LIB_DIR . PATH_SEPARATOR . __COMM_LIB_DIR . PATH_SEPARATOR . __HUSH_LIB_DIR . PATH_SEPARATOR . get_include_path());

/**
 * Global environment settings
 * TODO : should be changed by enviornment change !!!
 */
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/**
 * PMS server host and port
 * TODO : should be changed by enviornment change !!!
 */
define('PMS_SERVER_HOST', '127.0.0.1');
define('PMS_SERVER_PORT', '11111-11115');

/**
 * Set PMS engine
 */
require_once 'engine.inc';