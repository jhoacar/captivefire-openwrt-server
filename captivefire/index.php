<?php

require_once 'vendor/autoload.php';

use App\Kernel;

define('CAPTIVEFIRE_START', microtime(true));
$app = new Kernel();
$app->handle();
