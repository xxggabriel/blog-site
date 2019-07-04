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
        $page = new \App\Controller\Page\Page(null, false, '/admin');
        return $response->withStatus($statusCode)
            ->withHeader('Content-Type', 'text/html')
            ->write($page->setTpl('error/error.html', \App\Controller\Exceptions\ExceptionWeb::setError(null, $exception->getCode())));
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

$app->group('/admin/app', function() use($app){
    $app->get('', \App\Controller\Route\AdminController::class.":index")->setName('home-admin');

    $app->group('/user', function() use($app){

        $app->get('', \App\Controller\Route\AdminController::class.':listUsers')->setName('list-users');
        $app->get('/create', \App\Controller\Route\AdminController::class.':createUser')->setName('create-user');
        $app->post('/create', \App\Controller\Route\AdminController::class.':createUser')->setName('create-user-post');
        $app->get('/{username}', \App\Controller\Route\AdminController::class.':showUser')->setName('show-user');



    });
});

$app->run();