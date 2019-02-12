<?php

namespace api\Controllers;

use api\Errors\JsonError;
use api\Errors\JsonNotFound;
use api\Responses\CollectionResponse;
use api\Responses\JsonResponse;
use api\Responses\ResourceResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2018-12-13
 * Time: 08:50
 */

class Categories{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function all(RequestInterface $request, ResponseInterface $response){
    	//Récupération des catégories
        $cat = \api\Models\Categories::get();

        $response = CollectionResponse::make($response, ['categories' => $cat]);

        return $response;
    }

    public function single(RequestInterface $request, ResponseInterface $response, $args){
        //Récupération d'une categorie
    	$cat = \api\Models\Categories::where("id", $args['id'])->get();

    	$cat = \api\Models\Categories::find($args['id']);
        $response = ResourceResponse::make($response, ['categorie' => $cat]);
    }

    public function add(RequestInterface $request, ResponseInterface $response){
        $body = $request->getParsedBody();
        if(isset($body['nom']) && isset($body['description'])){
            $categorie = new \api\Models\Categories();
            $categorie->nom = $body['nom'];
            $categorie->description = $body['description'];
            $categorie->save();

            $response = $response->withAddedHeader('Location', $this->container->get('router')->pathFor('simple-categorie', ["id" => $categorie->id]));
            $response = JsonResponse::make($response, [$categorie], 201);
        } else {
            $response = JsonError::make($response, 'Bad request: Check your entity', 400);
        }

        return $response;
    }

    public function update(RequestInterface $request, ResponseInterface $response, $args){
        $body = $request->getParsedBody();

        if(isset($body['nom']) && isset($body['description'])){
            $categorie = \api\Models\Categories::find($args['id']);
            if($categorie){
                $categorie->nom = $body['nom'];
                $categorie->description = $body['description'];
                $categorie->save();

                $response = JsonResponse::make($response, [$categorie], 200);
            } else {
                $notFound = new JsonNotFound;
                $response = $notFound($request, $response);
            }
        } else {
            $response = JsonError::make($response, 'Bad request: Check your entity', 400);
        }

        return $response;
    }
}