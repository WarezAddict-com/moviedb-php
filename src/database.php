<?php

$capsule = new \Illuminate\Database\Capsule\Manager;

$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => APP_ROOT . '/database/moviedb-php.db',
    'prefix' => ''
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();
