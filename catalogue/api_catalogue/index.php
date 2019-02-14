<?php
use api\Controllers;
use api\Responses;
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
$app = new App($c);

// JSON API Routes definitions
$app->group('', function(){
   	//Afficher toute les categories
    $this->get('/categories', api\Controllers\Categories::class . ":all");

    //Afficher une seul categorie
    $this->get('/categories/{id}', api\Controllers\Categories::class . ":single")->setName('simple-categorie');

    // Sandwiches of categorie
    $this->get('/categories/{id}/sandwiches', api\Controllers\Categories::class . ":sandwiches")->setName('categorie-sandwiches');

    //Ajouter une categorie
    $this->post('/categories', api\Controllers\Categories::class . ":add")->setName("add-categorie");
    
    //Modifier une categorie
    $this->put('/categories/{id}', api\Controllers\Categories::class . ":update")->setName("update-categorie");
})->add(Responses\JsonHeaders::class);

// Run app
try {
    $app->run();
} catch (Exception $e) {
    die($e->getMessage());
}