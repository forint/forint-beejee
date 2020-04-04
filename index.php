<?php
// phpinfo();die;
session_start();
error_reporting(E_ALL);
set_error_handler(function(int $number, string $message, string $errfile , int $errline) {
    echo "Handler captured error $number: '$message'; In file : $errfile'; On line: ".$errline . PHP_EOL  ;
});

require __DIR__ .'/vendor/autoload.php';

$router = new App\Core\Router();


// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
//$router->add('film/{slug}', ['controller' => 'Film', 'action' => 'index']);
$router->add('{controller}/{id:\d+}/{action}');
$router->add('{controller}/{action}/film/{id:\S+}', ['controller' => 'Films', 'action' => 'index']);
$router->add('admin', ['namespace' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index']);
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->add('admin/{controller}/{action}/id/{id:\d+}', ['namespace' => 'Admin', 'controller' => 'Films', 'action' => 'edit']);

// Dispatch
$uriWithoutParams = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
$router->dispatch($uriWithoutParams);