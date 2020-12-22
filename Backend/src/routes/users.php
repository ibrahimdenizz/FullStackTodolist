<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/*
$app->get('/hello', function (Request $request, Response $response, $args) {
    $data = [0 =>"Hello world"];
    $send = json_encode($data);
    $response->getBody()->write($send);
    return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*');
});
*/
 
$app->post('/users', function (Request $request, Response $response) {
    

    $db = new Db();
    
    try{
        $db = $db->connect();

        $json = $request->getBody();
        $data = json_decode($json,true);

        if(empty($data)){
            $response->getBody()->write(json_encode(array("message" => "Unable to register the user.")));
            return $response
                    ->withStatus(400)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
        }else {

            
            $email = $data['email'];
            $name = $data['name'];
            $password = $data['password'];
            
            $email = htmlspecialchars($email);
            $name = htmlspecialchars($name);
            $password = htmlspecialchars($password);
            
            $table_name = 'users';
            
            $query = "SELECT * FROM ". $table_name . " WHERE email = :email";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindValue(':email',$email,PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(empty($data)) {
                
                
                $query = "INSERT INTO " . $table_name . 
                " SET email = :email,
                name = :name,
                pswd = :password";
                
                $stmt = $db->prepare($query);
                
                $stmt->bindValue(':email',$email,PDO::PARAM_STR);
                $stmt->bindValue(':name',$name,PDO::PARAM_STR);
                $password_hash = password_hash($password,PASSWORD_DEFAULT);
                $stmt->bindValue(':password',$password_hash,PDO::PARAM_STR);
                
                if($stmt->execute()){
                    $response->getBody()->write(json_encode(array("message" => "User was successfully registered.")));
                    return $response
                    ->withStatus(200)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
                }else {
                    $response->getBody()->write(json_encode(array("message" => "Unable to register the user.")));
                    return $response
                    ->withStatus(400)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
                }
            }else {
                $response->getBody()->write(json_encode(array("message" => "The Specified Account Already Exists")));
                
                return $response
                ->withStatus(409)
                ->withHeader('Content-Type','aplication/json')
                ->withHeader('Access-Control-Allow-Origin','*');
            }
        }
    }catch(PDOException $e) {

        $error = [
            "error" => [
                "text" => $e->getMessage(),
                "code" => $e->getCode()
                ]
            ];
        $error = json_encode($error);
    
        $response->getBody()->write($error);
        return $response
            ->withStatus(500)
            ->withHeader('Content-Type','aplication/json')
            ->withHeader('Access-Control-Allow-Origin','*');
    }

});
