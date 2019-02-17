<?php

namespace api\Controllers;

use api\Errors\JsonNotFound;
use api\Models\Commande;
use api\Responses\CollectionResponse;
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

//        var_dump($commande->items);
//        die();

        $response = ResourceResponse::make($response, ['commande' => $commande]);
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
}
