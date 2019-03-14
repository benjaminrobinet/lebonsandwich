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

    //Afficher toutes les commandes
    $this->get('/commandes', api\Controllers\Commandes::class . ":all")->setName('commandes');

    //Routes ayant besoin d'un token
    $app->group('', function (){
        //Afficher une commande
        $this->get('/commandes/{id}', api\Controllers\Commandes::class . ":single")->setName('commande');
        
        //Afficher les items d'une commande
        $this->get('/commandes/{id}/items', api\Controllers\Commandes::class . ":items")->setName('commande-items');
    })->add(Middlewares\AuthorizationToken::class); // Middleware needed token
    
    //Créer une commande
    $this->post('/commandes', api\Controllers\Commandes::class . ":create")->setName('create-commande');

    //Créer un client
    $this->post('/clients', api\Controllers\Client::class . ':create')->setName('create-client');

    //Route ayant besoin d'un Authorization Header
    $app->group("", function(){
        //Connexion d'un client
        $this->post("/clients/{id}/auth", api\Controllers\Client::class. ':auth')->setName("client-auth");

        //Infos d'un client
        $this->get("/clients/{id}", api\Controllers\Client::class. ':single')->setName('info-client');
    })->add(Middlewares\Authorization::class);

    //Historique des commandes d'un client
    $this->get("/clients/{id}/commandes", api\Controllers\Client::class. ':commandes')->setName("command-client");

})->add(Responses\JsonHeaders::class);

// Run app
try {
    $app->run();
} catch (Exception $e) {
    die($e->getMessage());
}