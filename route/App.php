<?php 
$conf = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$container = new \Slim\Container($conf);

$uri = explode('/',$_SERVER["REQUEST_URI"])[1];
if($uri == 'api'){
    
    $container['errorHandler'] = function ($c) {
        return function ($request, $response, $exception) use ($c) {
            $statusCode = $exception->getCode() ? $exception->getCode() : 500;
            
            return $c['response']->withJson(\App\Controller\Exceptions\ExceptionApi::setError($exception->getMessage() ,$exception->getCode()), $statusCode);
        };
    };
    $app = new \Slim\App($container);
    require_once __DIR__."/api/Api.php";
    $app->run();
    exit;
}

$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};




$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        $page = new \App\Controller\Page\Page();
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

$path = glob(__DIR__.'/web/*.php');

foreach ($path as $value) {
    require_once $value;
}


$app->run();