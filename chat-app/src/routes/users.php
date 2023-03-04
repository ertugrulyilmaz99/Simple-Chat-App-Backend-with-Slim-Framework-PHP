<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//$app = AppFactory::create();

$app -> get('/api/users', function (Request $request, Response $response) {
    $query = "SELECT * FROM Users";

    try{
        $dbConnection = new Db();
        $dbConnection = $dbConnection -> connect();

        $users = $dbConnection -> query($query) -> fetchall(PDO::FETCH_OBJ);

        $dbConnection = null;

        $response -> getBody() -> write(json_encode($users));
        return $response -> withHeader('content-type', 'application/json') -> withStatus(200);
    } catch (PDOException $e){
        $error = array(
            "message" => $e -> getMessage()
        );

        $response -> getBody() -> write(json_encode($error));
        return $response -> withHeader('content-type', 'application/json') -> withStatus(500);
    }

});
