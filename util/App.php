<?php

require __DIR__.'/../vendor/autoload.php';

use \App\Util\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;

Environment::load(__DIR__.'/../');

//Database settings
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);



//The URL is defined in the .env file btw
define('URL', getenv('URL'));
 
View::init([
    'URL' => URL
]);