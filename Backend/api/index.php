<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use \Firebase\JWT\JWT;

require __DIR__ . '/../vendor/autoload.php';
require "../src/config/db.php";

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->setBasePath("/todolist/api");


//Login routes
require_once "../src/routes/auth.php";
//todolist routes
require_once "../src/routes/todolist.php";
//register routes
require_once "../src/routes/users.php";

try {
    $app->run();     
} catch (Exception $e) {    
  // We display a error message
  die( json_encode(array("status" => "failed", "message" => "This action is not allowed"))); 
}