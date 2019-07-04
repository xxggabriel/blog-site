<?php 

$conf = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$container = new \Slim\Container($conf);

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        
        return $c['response']->withJson(\App\Controller\Exceptions\ExceptionApi::setError(
            $exception->getMessage(),
            $exception->getCode()), 
            $statusCode);
    };
};
$app = new \Slim\App($container);

$app->group("/api", function() use($app){
    $app->group("/v1", function() use($app){
        
        $app->group('/user', function() use($app){

            $app->get('', \App\Controller\Route\ApiController::class.":users");
            $app->post('', \App\Controller\Route\ApiController::class.":userCreate");

            $app->get('/{idUser}', \App\Controller\Route\ApiController::class.":user");
            $app->post('/{idUser}', \App\Controller\Route\ApiController::class.":userUpdate");

        });

        $app->group('/article', function() use($app){

            $app->get('', \App\Controller\Route\ApiController::class.":articles");
            $app->post('', \App\Controller\Route\ApiController::class.":articleCreate");

            $app->post('/{idArticle}', \App\Controller\Route\ApiController::class.":articleUpdate");

            // $app->group('/comment', function() use($app){

            //     $app->get('/{idArticle}', \App\Controller\Route\ApiController::class.":comments");
            //     $app->post('', \App\Controller\Route\ApiController::class.":commentCreate");

            // });
        });
        
    });
});

$app->run();
    
