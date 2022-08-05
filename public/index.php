<?php

/**
 * Captivefire.
 *
 * @author  Jhoan Carrero <jhoacar@captivefire.net>
 */
require_once dirname(__DIR__) . '/captivefire/vendor/autoload.php';

use App\Kernel;

define('CAPTIVEFIRE_START', microtime(true));
$app = new Kernel();
$app->handle();