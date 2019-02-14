<?php

namespace api\Controllers;

use api\Models\Commande;
use api\Responses\CollectionResponse;
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

    }

    public function all(RequestInterface $request, ResponseInterface $response){

        $status = $request->getQueryParam('status', 'any');
        $current_page = $request->getQueryParam('page', 1);

    	//Gestion des conditions
        $where = [];
        if($status !== 'any'){
            $where['status'] = $status;
        }


    	// if($request->getQueryParam("size") > 0){
    	// 	$com = $com->paginate(intval($request->getQueryParam("size")));
    	// }
    	// else{
    	// 	$com = $com->get();
    	// }

        $commandes = Commande::where($where)->paginate(10, ['id', 'nom', 'created_at', 'livraison', 'status'], 'page', $current_page);
        $commandes->withPath($this->container->router->pathFor('commandes') . '?status=' . $status);

        $result = [];

        foreach ($commandes as $commande){
            $result[] = [
                'commande' => $commande,
                'links' => [
                    'self' => [
                        'href' => $this->container->router->pathFor('commande', ['id' => $commande->id])
                    ]
                ]
            ];
        }

        $response = CollectionResponse::make($response, ['commandes' => $result], $commandes->total());
        return $response;
    }
}
