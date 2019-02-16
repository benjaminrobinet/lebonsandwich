<?php

namespace api\Controllers;

use api\Errors\JsonError;
use api\Errors\JsonNotFound;
use api\Models\Categorie;
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

class Categories{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function all(RequestInterface $request, ResponseInterface $response){
    	//Récupération des catégories
        $cat = Categorie::get();

        $response = CollectionResponse::make($response, ['categories' => $cat]);
        return $response;
    }

    public function single(RequestInterface $request, ResponseInterface $response, $args){
        //Récupération d'une categorie
    	$cat = Categorie::find($args['id']);

        if(!$cat){
            $notFound = new JsonNotFound();
            return $notFound($request, $response);
        }

    	$links = [
    	    "sandwiches" => [
    	        "href" => $this->container->router->pathFor('categorie-sandwiches', ['id' => $cat->id])
            ],
            "self" => [
                "href" => $this->container->router->pathFor('simple-categorie', ['id' => $cat->id])
            ]
        ];

        $response = ResourceResponse::make($response, ['categorie' => $cat], $links);
        return $response;
    }

    public function sandwiches(RequestInterface $request, ResponseInterface $response, $args){
        $cat = Categorie::find($args['id']);

        if(!$cat){
            $notFound = new JsonNotFound();
            return $notFound($request, $response);
        }

        $size = $request->getQueryParam('size', 10);
        $current_page = $request->getQueryParam('page', 1);

        $sandwiches = $cat->sandwiches()->paginate($size, ['*'], 'page', $current_page);
        $sandwiches->withPath($this->container->router->pathFor('categorie-sandwiches') . '?size=' . $size);

        $result = [];

        foreach ($sandwiches as $sandwich){
            $sandwich->links = [
                'self' => [
                    'href' => $this->container->router->pathFor('single-sandwich', ['id' => $sandwich->id])
                ]
            ];
            $result[] = $sandwich;
        }

        $links = [
            'next' => $sandwiches->nextPageUrl(),
            'prev' => $sandwiches->previousPageUrl(),
            'last' => $sandwiches->url($sandwiches->lastPage()),
            'first' => $sandwiches->url(1),
        ];

        $response = CollectionResponse::make($response, ['sandwiches' => $sandwiches], $links);
        return $response;
    }

    public function add(RequestInterface $request, ResponseInterface $response){
        $body = $request->getParsedBody();
        if(isset($body['nom']) && isset($body['description'])){
            $categorie = new Categorie();
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
            $categorie = Categorie::find($args['id']);
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