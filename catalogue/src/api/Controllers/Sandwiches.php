<?php

namespace api\Controllers;

use api\Errors\JsonError;
use api\Errors\JsonNotFound;
use api\Models\Categorie;
use api\Models\Sandwich;
use api\Responses\CollectionResponse;
use api\Responses\JsonResponse;
use api\Responses\ResourceResponse;
use Illuminate\Database\Eloquent\Collection;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2018-12-13
 * Time: 08:50
 */

class Sandwiches{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function all(RequestInterface $request, ResponseInterface $response){
    	//Récupération des catégories
        $sandwiches = Sandwich::all();

        $response = CollectionResponse::make($response, ['categories' => $sandwiches]);
        return $response;
    }

    public function single(RequestInterface $request, ResponseInterface $response, $args){
        //Récupération d'un sandwich
    	$sand = Sandwich::find($args['id']);

        if(!$sand){
            $notFound = new JsonNotFound();
            return $notFound($request, $response);
        }

    	$links = [
            "self" => [
                "href" => $this->container->router->pathFor('single-sandwich', ['id' => $sand->id])
            ]
        ];

        $response = ResourceResponse::make($response, ['sandwich' => $sand], $links);
        return $response;
    }

    public function categorie(RequestInterface $request, ResponseInterface $response, $args){
        $sand = Sandwich::find($args['id']);

        if(!$sand){
            $notFound = new JsonNotFound();
            return $notFound($request, $response);
        }

        $cat = $sand->categorie;

        $response = ResourceResponse::make($response, ['categorie' => $cat]);
        return $response;
    }
}
