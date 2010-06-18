<?php

/**
 * Set PMS engine and load PMS engine's classes
 */
define('PMS_ENGINE', 'sysv');

require_once 'Pms/Adaptor.php';

Pms_Adaptor::factory(PMS_ENGINE);