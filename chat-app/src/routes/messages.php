<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//$app = AppFactory::create();

// Get other users who has conversation with given user ID
$app -> get('/api/messages/userid={userid}', function(Request $request, Response $response, array $args) {
    $userid = $args['userid'];

    $query = "SELECT DISTINCT u1.id, u1.fullName, u2.id, u2.fullName 
    FROM Users u1, Users u2, Messages m 
    WHERE (m.senderId = $userid OR receiverId = $userid) AND (u2.id != $userid) AND (u2.id = m.senderId OR u2.id = m.receiverId) AND (u1.id = $userid)
    ";

    try{
        $dbConnection = new Db();
        $dbConnection = $dbConnection -> connect();

        $users = $dbConnection -> query($query) -> fetchall(PDO::FETCH_OBJ);

        if($users){
            $response -> getBody() -> write(json_encode($users));
            return $response 
            -> withHeader('content-type', 'application/json') 
            -> withStatus(200);
        }else {
            return $response 
            -> withHeader('content-type', 'application/json')
            -> withJson(
                array(
                    "error" => array(
                        "text" => "There is no such user or any conversation(s)."
                    )
                )
            )
            -> withStatus(400);
        }

        $dbConnection = null;

        
    } catch (PDOException $e){
        $error = array(
            "message" => $e -> getMessage(),
            "code" => $e -> getCode()
        );

        $response -> getBody() -> write(json_encode($error));
        return $response -> withHeader('content-type', 'application/json') -> withStatus(500);
    }
});

// Get messages between two users
$app -> get('/api/messages/userid={userid}/receiverid={receiverid}', function(Request $request, Response $response, array $args) {
    $userid = $args['userid'];
    $receiverid = $args['receiverid'];

    $query = "SELECT * 
    FROM Messages 
    WHERE senderId = $userid AND receiverId = $receiverid OR senderId = $receiverid AND receiverId = $userid";

    try{
        $dbConnection = new Db();
        $dbConnection = $dbConnection -> connect();

        $messages = $dbConnection -> query($query) -> fetchall(PDO::FETCH_OBJ);

        if($messages){
            $response -> getBody() -> write(json_encode($messages));
        return $response 
        -> withHeader('content-type', 'application/json') 
        -> withStatus(200);
        }else{
            return $response 
            -> withHeader('content-type', 'application/json')
            -> withJson(
                array(
                    "error" => array(
                        "text" => "There is no such message or user(s);"
                    )
                )
            )
            -> withStatus(400);
        }

        $dbConnection = null;

        
    } catch (PDOException $e){
        $error = array(
            "message" => $e -> getMessage(),
            "code" => $e -> getCode()
        );

        $response -> getBody() -> write(json_encode($error));
        return $response -> withHeader('content-type', 'application/json') -> withStatus(500);
    }

});

// Sending a message to a user
$app -> post('/api/messages/userid={userid}/receiverid={receiverid}/send', function(Request $request, Response $response, array $args){
    $userid = $args['userid'];
    $receiverid = $args['receiverid'];
    $content = $request -> getParam('content');

    try{
        $dbConnection = new Db();
        $dbConnection = $dbConnection -> connect();

        $checkUser = $dbConnection -> query("SELECT * FROM Users WHERE id = $userid") -> fetchall(PDO::FETCH_OBJ);
        $checkReceiver = $dbConnection -> query("SELECT * FROM Users WHERE id = $receiverid") -> fetchall(PDO::FETCH_OBJ);

        //Checks is both users are exists.
        if($checkUser && $checkReceiver && $content){
            $query = "INSERT INTO Messages (senderid, receiverId, content) VALUES (:userid,:receiverid,:content)";

            $query = $dbConnection -> prepare($query);
            $query -> bindParam(':userid', $userid);
            $query -> bindParam(':receiverid', $receiverid);
            $query -> bindParam(':content', $content);

            $result = $query -> execute();

            $dbConnection = null;

            $response -> getBody() -> write(json_encode($result));
            return $response -> withHeader('content-type', 'application/json') -> withStatus(200);
        }else{
            return $response 
            -> withHeader('content-type', 'application/json')
            -> withJson(
                array(
                    "error" => array(
                        "text" => "There is no such user(s) or content is empty."
                    )
                )
            )
            -> withStatus(400);
        }
        
    } catch (PDOException $e){
        $error = array(
            "message" => $e -> getMessage(),
            "code" => $e -> getCode()
        );

        $response -> getBody() -> write(json_encode($error));
        return $response -> withHeader('content-type', 'application/json') -> withStatus(500);
    }
});