<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;



// $app->get('/hello', function (Request $request, Response $response, $args) {
//     $data = [0 =>"Hello world"];
//     $send = json_encode($data);
//     $response->getBody()->write($send);
//     return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*');
// });

$app->post('/auth', function (Request $request, Response $response) {
    $db = new Db();
    
    try{
        $db = $db->connect();

        $json = $request->getBody();
        $data = json_decode($json,true);
        $secret_key = "O5sh4QRaU5XFLEGu";
        if(empty($data)){
            $response->getBody()->write(json_encode(array("message" => "Login failed.")));
                        return $response
                        ->withStatus(401)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');    
                   
        }else {
            if(!isset($data['jwt'])){
                if(isset($data['email'])&&isset($data['password'])) {

                    $email = $data['email'];
                    $password = $data['password'];
                    
                    $email = htmlspecialchars($email);
                    $password = htmlspecialchars($password);
                    
                    $table_name = 'users';
                    
                    $query = "SELECT id,email,name,pswd FROM ". $table_name ." WHERE email = :email  ";
                    
                    $stmt = $db->prepare($query);
                    
                    $stmt->bindValue(':email',$email,PDO::PARAM_STR);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(!empty($user)){
                        $password2 = $user['pswd'];
                        $id = $user['id'];
                        $name = $user['name'];
                        if(password_verify($password,$password2)){
                            $issuer_claim = "todolist";
                            $audience_claim = $id;
                            $issuedat_claim = time();
                            $notbefore_claim = $issuedat_claim + 10;
                            $expire_claim = $issuedat_claim + (60*60*24*30);
                            $token = array(
                                "iss" => $issuedat_claim,
                                "aud" => $audience_claim,
                                "iat" => $issuedat_claim,
                                "nbf" => $notbefore_claim,
                                "exp" => $expire_claim,
                                "data" => array(
                                    "id" => $id,
                                    "name" => $name,
                                    "email" => $email
                                    )
                                );
                                
                                $jwt = JWT::encode($token,$secret_key);
                                
                                $response->getBody()->write(json_encode(array(
                                    "message" => "Successful login.",
                                    "jwt" => $jwt,
                                    "email" => $email,
                                    "expireAt" => $expire_claim
                                )));
                                
                                return $response
                                ->withStatus(200)
                                ->withHeader('Access-Control-Allow-Origin','*')
                                ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
                                ->withHeader('Content-Type','aplication/json');
                                
                                
                        }else {
                            $response->getBody()->write(json_encode(array("message" => "Login failed.")));
                            return $response
                            ->withStatus(401)
                            ->withHeader('Content-Type','aplication/json')
                            ->withHeader('Access-Control-Allow-Origin', '*');    
                        }
                            
                    }else {
                        $response->getBody()->write(json_encode(array("message" => "User not found.")));
                        return $response
                        ->withStatus(404)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');
                    }
                }else {
                    if(!isset($data['email'])){
                        $response->getBody()->write(json_encode(array("message" => "Required email to login")));
                        return $response
                        ->withStatus(400)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');    
                    }
                    if(!isset($data['password'])){
                        $response->getBody()->write(json_encode(array("message" => "Required password to login")));
                        return $response
                        ->withStatus(400)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');    
                    }
                }
            }else {
                $jwt = $data['jwt'];
                try{
                
                    $decoded = JWT::decode($jwt,$secret_key,array('HS256'));

                    $response->getBody()->write(json_encode(array(
                        "message" => "Successful login.",
                        "jwt" => $jwt
                    )));

                    return $response
                        ->withStatus(200)
                        ->withHeader("Content-Type", "aplication/json")
                        ->withHeader("Access-Control-Allow-Origin", "*");

                } catch(Exception $e) {
                    $response->getBody()->write(json_encode(array(
                        "message" => "Access denied.",
                        "error" => $e->getMessage()
                    )));

                    return $response
                        ->withStatus(401)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin','*');
                }
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
        return $response->withHeader('Content-Type', 'application/json');
    }
});