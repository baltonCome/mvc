<?php

require __DIR__.'/util/App.php';

use \App\Http\Router;
$router = new Router(URL);

include __DIR__.'/routes/pages.php';
 
$router -> run() -> sendResponse();