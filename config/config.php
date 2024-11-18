<?php

define('APP_PATH', dirname(__DIR__));

const PATH = 'http://frame-work';

const DATABASE_CONFIG = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'db_name' => 'framework',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];