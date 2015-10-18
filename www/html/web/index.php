<?php

require_once '../bootstrap.php';
require_once '../AddressApplication.php';
require_once '../vendor/autoload.php';

$app = new AddressApplication(false);
$app->run();
