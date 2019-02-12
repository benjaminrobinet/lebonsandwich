<?php

namespace api\Controllers;

use api\Responses\JsonResponse;
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
    	
    	$com = \api\Models\Commande::paginate(10);

    	//Gestion des conditions
    	// if($request->getQueryParam("s") >= 0){
    	// 	$com = $com->where('status', $request->getQueryParam("s"));
    	// }

    	// if($request->getQueryParam("size") > 0){
    	// 	$com = $com->paginate(intval($request->getQueryParam("size")));
    	// }
    	// else{
    	// 	$com = $com->get();
    	// }

    	foreach ($com as $item) 
    	{
    	 	var_dump($item);
    	}

        //$response = CollectionResponse::make($response, ['commandes' => $com]);
        //return $response;
    }
}
