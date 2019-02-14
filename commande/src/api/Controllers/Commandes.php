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

    public function all(RequestInterface $request, ResponseInterface $response){

        $status = $request->getQueryParam('page', 1);
        $current_page = $request->getQueryParam('page', 1);

    	//Gestion des conditions
        $where = [];
        $where['status'] = $request->getQueryParam('status', '*');



    	// if($request->getQueryParam("size") > 0){
    	// 	$com = $com->paginate(intval($request->getQueryParam("size")));
    	// }
    	// else{
    	// 	$com = $com->get();
    	// }

        $commandes = Commande::where($where)->paginate(10, ['*'], 'page', $current_page);
        $commandes->withPath($this->container->router->pathFor('commandes'));

        var_dump($commandes->toArray());

    	die();

        //$response = CollectionResponse::make($response, ['commandes' => $com]);
        //return $response;
    }
}
