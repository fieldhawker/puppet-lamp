<?php

require 'core/ClassLoader.php';

// 一意なID
//echo sha1( uniqid( mt_rand() , true ) );

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__) . '/core');
$loader->registerDir(dirname(__FILE__) . '/models');
$loader->register();
