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
 
// GET todolist
$app->get('/list/{user_id}', function (Request $request, Response $response,array $args) {
    $db = new Db();
    
    try{
        $db = $db->connect();
        
        if(isset($args['user_id'])){

            $user_id = $args['user_id'];

            $user_id = htmlspecialchars($user_id);

            $table_name = "list";

            $query = "SELECT * FROM ". $table_name ." WHERE user_id = :user_id";

            $stmt = $db->prepare($query);

            $stmt->bindValue(":user_id", $user_id,PDO::PARAM_INT);
            if($stmt->execute()){
   
                $todolist = $stmt->fetchAll(PDO::FETCH_OBJ);
                if(!empty($todolist)){

                    $data =json_encode($todolist);

                    $response->getBody()->write($data);
                    return $response
                    ->withStatus(200)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
                    
                }else {
                    $response->getBody()->write(json_encode(array("message" => "404 Not Found")));
                    return $response
                    ->withStatus(404)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
                }

            }else {
                $response->getBody()->write(json_encode(array("message" => "Unable to get the todolist.")));
                return $response
                ->withStatus(400)
                ->withHeader('Content-Type','aplication/json')
                ->withHeader('Access-Control-Allow-Origin', '*');
            }

        }else {
            $response->getBody()->write(json_encode(array("message" => "404 Not Found")));
            return $response
            ->withStatus(404)
            ->withHeader('Content-Type','aplication/json')
            ->withHeader('Access-Control-Allow-Origin', '*');
        
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
// Add todo
$app->post('/list', function (Request $request, Response $response) {
    $db = new Db();
    
    try{
        $db = $db->connect();

        $json = $request->getBody();
        $data = json_decode($json,true);
        if(empty($data)){
            $response->getBody()->write(json_encode(array(
                "message" => "Not Found"
            )));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'aplication/json')
                ->withHeader('Access-Control-Allow-Origin', '*');
        }else {
            if(isset($data['content'])&&isset($data['is_check'])&&isset($data['user_id'])&&isset($data['day_id'])){

                $content = $data['content'];
                $is_check = $data['is_check'];
                $user_id = $data['user_id'];
                $day_id = $data['day_id'];
                
                $content = htmlspecialchars($content);
                $is_check = htmlspecialchars($is_check);
                $user_id = htmlspecialchars($user_id);
                $day_id = htmlspecialchars($day_id);
                
                $table_name = "list";
                
                $query = "INSERT INTO " . $table_name . 
                " (content,is_check,user_id,day_id) VALUES(:content,:is_check,:user_id,:day_id)";
                
                $stmt = $db->prepare($query);
                $stmt->bindValue(':content',$content,PDO::PARAM_STR);
                $stmt->bindValue(':is_check',$is_check,PDO::PARAM_BOOL);
                $stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
                $stmt->bindValue(':day_id',$day_id,PDO::PARAM_INT);
                
                if($stmt->execute()){
                    $response->getBody()->write(json_encode(array("message" => "Todo was successfully added")));
                    return $response
                    ->withStatus(200)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
                }else {
                    $response->getBody()->write(json_encode(array("message" => "Unable to add the todo")));
                    return $response
                    ->withStatus(404)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
                }
            }else {
                $response->getBody()->write(json_encode(array("message" => "Missing content")));
                    return $response
                    ->withStatus(400)
                    ->withHeader('Content-Type','aplication/json')
                    ->withHeader('Access-Control-Allow-Origin', '*');
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

//Delete todo

$app->delete('/list/{id}', function (Request $request, Response $response,array $args) {
    $db = new Db();
    
    try{
        $db = $db->connect();
        $id = $args['id'];
        
        $id = htmlspecialchars($id);

        $table_name = "list";
        $query = "DElETE FROM ". $table_name . " WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$id,PDO::PARAM_INT);

        if($stmt->execute()) {
            $response->getBody()->write(json_encode(array("message" => "Todo was successfully deleted")));
            return $response
            ->withStatus(200)
            ->withHeader('Content-Type','aplication/json')
            ->withHeader('Access-Control-Allow-Origin', '*');
        }else {
            $response->getBody()->write(json_encode(array("message" => "Content was not deleted")));
            return $response
            ->withStatus(404)
            ->withHeader('Content-Type','aplication/json')
            ->withHeader('Access-Control-Allow-Origin', '*');
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

// UPDATE todo 

$app->put('/list/{id}', function (Request $request, Response $response,array $args) {
    $db = new Db();
    
    try{
        $db = $db->connect();

        $id = $args['id'];
        $id = htmlspecialchars($id);
        if(!empty($id)) {
            $data = $request->getBody();
            $data = json_decode($data,true);
            if(isset($data['content'])||isset($data['is_check'])) {

                if(isset($data['content'])&&isset($data['is_check']))
                {
                    $content = $data['content'];
                    $is_check = $data['is_check'];
                    $content = htmlspecialchars($content);
                    $is_check = htmlspecialchars($is_check);

                    $table_name = "list";
                    
                    $query = "UPDATE ". $table_name . " SET content = :content, is_check = :is_check WHERE id = :id";
                    
                    $stmt = $db->prepare($query);
                    
                    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
                    $stmt->bindValue(":content",$content,PDO::PARAM_STR);
                    $stmt->bindValue(":is_check",$is_check,PDO::PARAM_BOOL);
                    
                    if($stmt->execute()) {
                        $response->getBody()->write(json_encode(array("message" => "Todo was successfully updated")));
                        return $response
                        ->withStatus(200)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');
                    }else {
                        $response->getBody()->write(json_encode(array("message" => "Todo was not updated")));
                        return $response
                        ->withStatus(404)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');
                    }
                }

                if(isset($data['content']))
                {
                    $content = $data['content'];
                    $content = htmlspecialchars($content);

                    $table_name = "list";
                    
                    $query = "UPDATE ". $table_name . " SET content = :content WHERE id = :id";
                    
                    $stmt = $db->prepare($query);
                    
                    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
                    $stmt->bindValue(":content",$content,PDO::PARAM_STR);
                    
                    if($stmt->execute()) {
                        $response->getBody()->write(json_encode(array("message" => "Content was successfully updated")));
                        return $response
                        ->withStatus(200)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');
                    }else {
                        $response->getBody()->write(json_encode(array("message" => "Content was not updated")));
                        return $response
                        ->withStatus(404)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');
                    }
                }

                if(isset($data['is_check']))
                {
                    $is_check = $data['is_check'];
                    $is_check = htmlspecialchars($is_check);
                    
                    $table_name = "list";
                    
                    $query = "UPDATE ". $table_name . " SET is_check = :is_check WHERE id = :id";
                    
                    $stmt = $db->prepare($query);
                    
                    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
                    $stmt->bindValue(":is_check",$is_check,PDO::PARAM_BOOL);
                    
                    if($stmt->execute()) {
                        $response->getBody()->write(json_encode(array("message" => "isCheck was successfully updated")));
                        return $response
                        ->withStatus(200)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');
                    }else {
                        $response->getBody()->write(json_encode(array("message" => "isCheck was not updated")));
                        return $response
                        ->withStatus(404)
                        ->withHeader('Content-Type','aplication/json')
                        ->withHeader('Access-Control-Allow-Origin', '*');
                    }
                }


            }else {
                $response->getBody()->write(json_encode(array("message" => "Missing Request")));
                return $response
                ->withStatus(400)
                ->withHeader('Content-Type','aplication/json')
                ->withHeader('Access-Control-Allow-Origin', '*');    
            }
        }else {
            $response->getBody()->write(json_encode(array("message" => "404 Not Found")));
            return $response
            ->withStatus(404)
            ->withHeader('Content-Type','aplication/json')
            ->withHeader('Access-Control-Allow-Origin', '*');
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