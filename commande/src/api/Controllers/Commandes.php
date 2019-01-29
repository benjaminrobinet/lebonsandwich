<?php

namespace api\Controllers;

use api\Responses\JsonResponse;
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

    	if(isset($_GET['s']) && intval($_GET["s"]) != 0)
    	{
    		$com = \api\Models\Commande::where('status', "=", $_GET['s'])->get();
    	}
    	else if(isset($_GET['page']) && intval($_GET["page"]) > 0)
    	{
    		$com = \api\Models\Commande::skip(
											(intval($_GET["page"]) * 10) - 10
										)
										->take(
											(isset($_GET["size"]) && intval($_GET["size"]) > 0) ? intval($_GET["size"]) : 10
										)->get();
    	}
    	else if(isset($_GET['page']) && intval($_GET["page"]) <= 0)
    	{
    		$com = \api\Models\Commande::take(10)->get();
    	}
    	else
    	{
    		$com = \api\Models\Commande::get();
    	}

    	$commandes = [];
    	foreach ($com as $commande) 
    	{
    		$commandes[] = [ 
    			"command" => [
    				"id" => $commande->id,
    				"nom" => $commande->nom,
    				"created_at" => $commande->created_at,
    				"livraison" => $commande->livraison,
    				"status" => $commande->status
    			],
    			"links" => [
    				"self" => [
    					"href" => "commands/".$commande->id 
    				]
    			]
    		];
    	}

    	//passer en paramètre le nombre d'element dans la base (a cause de la pagination)
    	$count = \api\Models\Commande::count();

    	$pagination = [
			"next" => [
				"href" => "/commandes?page=".(($_GET["page"] > 1) ? ((int)((($count / intval($_GET["size"])) >= intval($_GET["page"])))) ? (intval($_GET["page"]) + 1) : (int)(($count / intval($_GET["size"])) + 1) : 2)."&size=".intval($_GET["size"])
			],
			"prev" => [
				"href" => "/commandes?page=".(($_GET["page"] > 1) ? ((int)((($count / intval($_GET["size"]) + 1) >= intval($_GET["page"])))) ? (intval($_GET["page"]) - 1) : (int)(($count / intval($_GET["size"]))) : 1)."&size=".intval($_GET["size"])
			],
			"last" => [
				"href" => "/commandes?page=".((int)(($count / intval($_GET["size"])) + 1))."&size=".intval($_GET["size"])
			],
			"first" => [
				"href" => "/commandes?page=1&size=".intval($_GET["size"])
			]
    	];
    	
        $response = JsonResponse::make($response, $commandes, $count, $pagination);
    }
}
