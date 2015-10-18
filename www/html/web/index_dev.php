<?php

require_once '../bootstrap.php';
require_once '../AddressApplication.php';
require_once '../vendor/autoload.php';

$debug_mode = true;
$app = new AddressApplication($debug_mode);
$app->run();
