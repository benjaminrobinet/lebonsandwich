<?php
use api\Controllers;
use api\Responses;
use api\Errors;
use Slim\App;
use Slim\Container;

require_once('../src/vendor/autoload.php');

$configuration = ['settings' => [
    'displayErrorDetails' => true,
    'production' => false ]
];

$c = new Container($configuration);
Errors\JsonErrorsDispatcher::dispatch($c);
$app = new App($c);

// JSON API Routes definitions
$app->group('', function(){ // Only for group logic to add a middleware (https://www.slimframework.com/docs/v3/objects/router.html#route-groups)
    $this->get('/categories', Controllers\Categories\Categories::class . ':all');
})->add(Responses\JsonHeaders::class);

// Run app
try {
    $app->run();
} catch (Exception $e) {
    die($e->getMessage());
}