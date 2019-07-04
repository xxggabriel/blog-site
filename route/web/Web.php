<?php

$conf = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$container = new \Slim\Container($conf);

    
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        
        return $response->withStatus($statusCode)
            ->withHeader('Content-Type', 'text/html')
            ->write(\App\Controller\Exceptions\ExceptionWeb::setError(null, $exception->getCode()));
    };
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $page = new \App\Controller\Page\Page();
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write($page->setTpl("error/404.html"));
    };
};

$app = new \Slim\App($container);

$app->add(new \Slim\HttpCache\Cache('public', 86400));


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