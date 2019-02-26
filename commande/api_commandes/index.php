<?php
use api\Controllers;
use api\Responses;
use api\Middlewares;
use api\Errors;
use scripts\Database;
use Slim\App;
use Slim\Container;

require_once('../src/vendor/autoload.php');

$configuration = ['settings' => [
    'displayErrorDetails' => true,
    'production' => false ]
];

$conf = parse_ini_file("configuration.ini", true);
Database::init($conf['database']);

$c = new Container($configuration);
Errors\JsonErrorsDispatcher::dispatch($c);
scripts\RegisterServices::register($c, $conf["services"]);
$app = new App($c);

// JSON API Routes definitions
$app->group('', function() use($app){ // Only for group logic to add a middleware (https://www.slimframework.com/docs/v3/objects/router.html#route-groups)
    $this->get('/commandes', api\Controllers\Commandes::class . ":all")->setName('commandes');
    $app->group('', function (){
        $this->get('/commandes/{id}', api\Controllers\Commandes::class . ":single")->setName('commande');
        $this->get('/commandes/{id}/items', api\Controllers\Commandes::class . ":items")->setName('commande-items');
    })->add(Middlewares\AuthorizationToken::class); // Middleware needed token
    $this->post('/commandes', api\Controllers\Commandes::class . ":create")->setName('create-commande');
})->add(Responses\JsonHeaders::class);

// Run app
try {
    $app->run();
} catch (Exception $e) {
    die($e->getMessage());
}