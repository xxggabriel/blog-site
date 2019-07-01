<?php 

namespace App\Controller\Route;

use App\Controller\Page\Page;
use App\Model\Article\Article;

class WebController
{
    private $page;

    public function __construct($container)
    {
        $this->page = new Page($container);
    }

    public function index($req, $res, $args)
    {
        throw new \Exception("Error Processing Request", 404);
        
        $article = new Article();
        $this->page->setTpl('index.html', [
            "newArticles" => $article->showShortArticle(10)
        ]);
    }

    public function setSidebar()
    {
        $article = new Article();
        return [
                "sidebar" =>    [
                    "categories" => $article->readCategory(5),
                    "popularPosts" => $article->popularPosts(5)
                    ]
                ];
    }

    public function setHeader($post)
    {
        return [
            "header" => [
            "head_title" => $post["title"],
            "head_description" => $post["description"],
            "head_keyworkds" => $post["tags"],
            "head_author" => $post["username"]
            ]
        ];
    }

    public function showError($res, $tpl)
    {
        
        return $res->withStatus(404)
        ->withHeader('Content-Type', 'text/html')
        ->write($this->page->setTpl($tpl));
    }

    public function showArticle($req, $res, $args)
    {
        
        $article = new Article();
        $post = $article->read($args["url"])[0];

        if(empty($post)){
            return $this->showError($res, "error/404.html");
        }

        $this->page->setTpl('article-show.html', array_merge([     
            "article" => $post,   
        ], $this->setSidebar(), $this->setHeader($post)));
    }

    public function showTag($req, $res, $args)
    {
        echo "Tag: ".$args["tag"];
    }

    public function showUser($req, $res, $args)
    {
        echo "User: ". $args["user"];
    }

    

}