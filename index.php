<?php

require __DIR__.'/vendor/autoload.php';

use \App\Http\Router;
use \App\Util\View;


define('URL', 'http://localhost:3000/mvc');
 
View::init([
    'URL' => URL
]);

$router = new Router(URL);

include __DIR__.'/routes/pages.php';
 
$router -> run() -> sendResponse();