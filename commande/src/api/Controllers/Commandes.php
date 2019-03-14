<?php

namespace api\Controllers;

use api\Errors\JsonError;
use api\Errors\JsonNotFound;
use api\Models\Commande;
use api\Models\Item;
use api\Models\Clients;
use api\Responses\CollectionResponse;
use api\Responses\ResourceResponse;
use DateTime;
use GuzzleHttp\Psr7\Response;
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
        $commande = new Commande();

        //Si le client passe son ID
        if(!empty($body["client_id"])){
            $current_client_id = Clients::find($body["client_id"]);

            if($current_client_id){
                $commande->client_id = $body["client_id"];
                $commande->nom = $current_client_id->fullname;
                $commande->mail = $current_client_id->email;
            }else{
                return JsonError::make($response, 'Bad Request: Client isn\'t exist', 400);
            }
        //S'il passe son nom et prénom
        }else if(!empty($body['nom']) && !empty($body['mail']) && !empty($body['livraison'])){
            $commande->nom = $body['nom'];
            $commande->mail = $body['mail'];
        } else {
            $jsonError = new JsonError();
            $jsonError->make($response, 'Bad Request', 400);
        }

        //Création d'un ID de commande
        $id = $uuid4 = Uuid::uuid4();
          
        //Token de la commande  
        $token = openssl_random_pseudo_bytes(32);
        $token = bin2hex($token);

        //Création de la commande
        $commande->id = $id;
        $commande->token = $token;
        $commande->livraison = $body['livraison'];
        $catalogue_service = $this->container->get('catalogue');

        $items = [];
        $montant = 0;

        //S'il y a des items
        if(!empty($body["items"])){
            foreach ($body["items"] as $item){
                /** @var Response $res */
                $res = $catalogue_service->request('GET', $item['uri']);
                $resBody = $res->getBody();
                $resBody = json_decode($resBody, true);
                if($res->getStatusCode() === 200){
                    // Create new item
                    $itemM = new Item();

                    // Define item
                    $itemM->uri = $item['uri'];
                    $itemM->libelle = $resBody['sandwich']['nom'];
                    $itemM->tarif = $resBody['sandwich']['prix'];
                    $itemM->quantite = $item['q'];

                    $items[] = $itemM;

                    $montant += $resBody['sandwich']['prix'] * $item['q'];
                }
            }
        }

        $commande->montant = $montant;
        $commande->items()->saveMany($items);

        //Sauvegarder la commande
        $commande->save();

        $commande = Commande::with('items')->find($commande->id);

        $links = [
            'self' => $this->container->router->pathFor('commande', ['id' => $commande->id]),
            'items' => $this->container->router->pathFor('commande-items', ['id' => $commande->id])
        ];

        $response = $response->withAddedHeader('Location', $this->container->router->pathFor('commande', ['id' => $commande->id]));
        $response = ResourceResponse::make($response, ['commande' => $commande], $links, 201);
        return $response;
    }
}
