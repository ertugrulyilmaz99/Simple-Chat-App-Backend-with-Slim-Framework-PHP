<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

//DB Connection
require __DIR__ . '/../src/config/db.php';

$app = AppFactory::create();

//Needed routes
require __DIR__ . '/../src/routes/users.php';
require __DIR__ . '/../src/routes/messages.php';

$app->run();