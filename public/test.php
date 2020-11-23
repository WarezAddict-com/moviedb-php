<?php

// Define
define('APP_ROOT', dirname(__DIR__));

// Autoload
require_once APP_ROOT . '/vendor/autoload.php';

// Use
use \Turbo\Helpers\Snipz;

$list = array(
    array("Peter", "Griffin" ,"Oslo", "Norway"),
    array("Glenn", "Quagmire", "Oslo", "Norway"),
);

$email = 'h22turbo@suddenlink.net';

$url = 'http://eliteminingrepair.com';

$snipz = new Snipz;

echo $snipz->getJson($url);
