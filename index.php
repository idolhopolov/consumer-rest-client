<?php

use Merce\Consumer\RestClient\Router;
use Merce\Consumer\RestClient\Controller\Main;

require __DIR__ . '/vendor/autoload.php';

$router = new Router();

$router->register('/home', [Main::class, 'index']);

echo $router->resolve($_SERVER['REQUEST_URI']);