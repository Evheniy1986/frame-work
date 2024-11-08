<?php


//define('APP_PATH', dirname(__DIR__));

require  __DIR__ . '/../config/config.php';
require APP_PATH . '/vendor/autoload.php';
require  APP_PATH. '/helpers/helpers.php';


$app = new \Framework\App();
require APP_PATH . '/config/routes.php';

//dump($app->request->getPath());


$app->run();



