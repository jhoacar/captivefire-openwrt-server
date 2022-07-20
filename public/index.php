<?php

/**
 * Captivefire.
 *
 * @author  Jhoan Carrero <jhoacar@captivefire.net>
 */

use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload.php';


define('CAPTIVEFIRE_START', microtime(true));

$config = require_once dirname(__DIR__) . '/config/app.php';
$app = new Kernel($config);
$app->handle();
