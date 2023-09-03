<?php

$db = [
    'host' => 'localhost',
    'port' => '3306',
    'charset' => 'utf8',
    'dbname' => 'sibd_andredias',
    'username' => 'sibd_andredias',
    'password' => 'HDU3HucY02bL1T7s'
];

define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$DEBUG = ' -=[ D E B U G ]=-&nbsp; <br>';
    
define('SUBJ','SIBD');
define('AUTHOR','André Dias');
define('ANO LETIVO','2021.2022');

