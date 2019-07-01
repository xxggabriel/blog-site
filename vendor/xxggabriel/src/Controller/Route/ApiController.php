<?php 

namespace App\Controller\Route;

use App\Model\User\User;
use App\Model\Article\Article;
use App\Model\Article\Comment;
use App\Model\User\Api;
use \App\Controller\Exceptions\ExceptionApi;

class ApiController
{
    private $api;
    
    public function __construct($container)
    {
        $this->api = new Api(); 
        $this->verifyTokenAndUserId();      
    }

    private function verifyTokenAndUserId()
    {
            if($_SERVER['REQUEST_METHOD'] == "GET" && empty($_GET["token"])){
                throw new \Exception("Token de acesso não informado.", 401);
                    
            } else if($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["token"])){   
                throw new \Exception("Token de acesso não informado.", 401);
                    
            }

            if($_SERVER['REQUEST_METHOD'] == "GET" && empty($_GET["userId"])){
                throw new \Exception("userId de acesso não informado.", 401);

            } else if($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["userId"])){
                throw new \Exception("userId de acesso não informado.", 401);
                
            }       
            if(!$this->api->verifyTokenApi(
                    $_SERVER['REQUEST_METHOD'] == "POST" ? $_POST["userId"] : $_GET["userId"],
                    $_SERVER['REQUEST_METHOD'] == "POST" ? $_POST["token"] : $_GET["token"]
                )
            ){
                throw new \Exception("Acesso negado, tente falar com administrador da API.");
                
            } 
        
    }

    public function user($req, $res, $args)
    {
        
        $user = new User;
        
        return $res->withJson($user->read($args["idUser"]), 201);
        
    }

    public function users($req, $res, $args)
    {
        
        $user = new User;
        
        return $res->withJson($user->read(), 201);
        
    }

    public function userCreate($req, $res, $args)
    {
        
        $user = new User();
        
        return $user->create($_POST["name"], $_POST["username"], $_POST["email"], $_POST["password"]);
        
    }

    public function userUpdate($req, $res, $args)
    {
        
        $user = new User();
        
        return $user->update($args["idUser"], $_POST);
        
    }

    public function articles($req, $res, $args)
    {
        
       
        $article = new Article();
        return $res->withJson($article->read(empty($_GET["url"]) ? null : $_GET["url"],
                                             empty($_GET["limit"]) ? null : $_GET["limit"]), 200);
        
    }

    public function articleCreate($req, $res, $args)
    {
        
        $article = new Article();
        return ($article->create($_POST["idUser"], $_POST["title"], $_POST["description"], $_POST["body"], $_FILES["image"], $_POST["url"], $_POST["tags"], $_POST["idCategory"]))? $res->withJson(true, 201) : $res->withJson(ExceptionApi::setError("Erro ao criar o artigo.", null), 406);
        
    }

    public function articleUpdate($req, $res, $args)
    {
        
        $article = new Article();
        return $article->update($args["idArticle"], $_POST);
        
    }

    public function comments($req, $res, $args)
    {
        
        $comment = new Comment();
        
        return $res->withJson($comment->read($args["idArticle"], null, null), 201);
        
                
    }

    public function commentCreate($req, $res, $args)
    {
        
        $comment = new Comment();
        
        return $comment->createComment($_POST["idArticle"], $_POST["idUser"], $_POST["body"]);
        
    }

    

}