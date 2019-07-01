<?php



$app = new \Slim\App($container);
$app->add(new \Slim\HttpCache\Cache('public', 86400));
$route = new \App\Controller\Route\AppController;

$app->get('/', \App\Controller\Route\WebController::class.":index")->setName('home');

$app->group('/article', function() use($app){

    $app->get('/{url}', \App\Controller\Route\WebController::class.":showArticle")->setName('article-show');

});

$app->group('/tag', function() use($app){

    $app->get('/{tag}', \App\Controller\Route\WebController::class.":showTag")->setName("tag-show");

});

$app->group('/user', function() use($app){

    $app->get('/{user}', \App\Controller\Route\WebController::class.":showUser")->setName("user-show");

});

$app->run();