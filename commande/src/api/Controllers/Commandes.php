<?php

namespace api\Controllers;

use api\Errors\JsonError;
use api\Errors\JsonNotFound;
use api\Models\Commande;
use api\Responses\CollectionResponse;
use api\Responses\ResourceResponse;
use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2018-12-13
 * Time: 08:50
 */

class Commandes{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function single(RequestInterface $request, ResponseInterface $response, $args){
        $commande = Commande::with('items')->find($args['id']);

        if(!$commande){
            $notFound = new JsonNotFound;
            return $notFound($request, $response);
        }

        $links = [
            "self" => $this->container->router->pathFor('commande', ['id' => $commande->id]),
            "items" => $this->container->router->pathFor('commande-items', ['id' => $commande->id]),
        ];

        $response = ResourceResponse::make($response, ['commande' => $commande], $links);
        return $response;
    }

    public function all(RequestInterface $request, ResponseInterface $response){

        $status = $request->getQueryParam('status', 'any');
        $size = $request->getQueryParam('size', 10);
        $current_page = $request->getQueryParam('page', 1);

    	//Gestion des conditions
        $where = [];
        if($status !== 'any'){
            $where['status'] = $status;
        }

        $commandes = Commande::where($where)->paginate($size, ['id', 'nom', 'created_at', 'livraison', 'status'], 'page', $current_page);
        $commandes->withPath($this->container->router->pathFor('commandes') . '?status=' . $status . '&size=' . $size);

        $result = [];

        foreach ($commandes as $commande){
            $commande->links = [
                'self' => [
                    'href' => $this->container->router->pathFor('commande', ['id' => $commande->id])
                ]
            ];
            $result[] = $commande;
        }

        $links = [
            'next' => $commandes->nextPageUrl(),
            'prev' => $commandes->previousPageUrl(),
            'last' => $commandes->url($commandes->lastPage()),
            'first' => $commandes->url(1),
        ];

        $response = CollectionResponse::make($response, ['commandes' => $result], $commandes->total(), $links);
        return $response;
    }

    public function create(RequestInterface $request, ResponseInterface $response){
        $body = $request->getParsedBody();

        if(!empty($body['nom'])
        && !empty($body['mail'])
        && !empty($body['livraison'])){
            $commande = new Commande();

            try {
                $id = $uuid4 = Uuid::uuid4();
            } catch (\Exception $e) {
                die($e->getMessage());
            }
            $token = openssl_random_pseudo_bytes(32);
            $token = bin2hex($token);

            //Création de la commande
            $commande->id = $id;
            $commande->token = $token;
            $commande->nom = $body['nom'];
            $commande->mail = $body['mail'];
            $commande->livraison = $body['livraison'];
            $commande->montant = 0;

            $catalogue_service = $this->container->get('catalogue');

            //S'il y a des items
            if(!empty($body["items"])){
                foreach ($body["items"] as $item){
                    
                }
            }

            $req = new Request('GET', "/sandwiches/1", ["Content-Type"=>"application/json"]);

            var_dump($test); die();
            $test = $catalogue_service->send($req);
            var_dump($test->getBody()); die();

            //Sauvegarder la commande
            $commande->save();

            $response = $response->withAddedHeader('Location', $this->container->router->pathFor('commande', ['id' => $commande->id]));
            $response = ResourceResponse::make($response, ['commande' => $commande], null, 201);
            return $response;
        } else {
            $jsonError = new JsonError();
            $jsonError->make($response, 'Bad Request', 400);
        }

    }
}
