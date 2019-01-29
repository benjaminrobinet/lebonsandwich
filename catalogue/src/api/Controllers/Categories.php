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

class Categories{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function all(RequestInterface $request, ResponseInterface $response){
    	$cat = \api\Models\Categories::get();

        $response = JsonResponse::make($response, $cat);
    }

    public function single(RequestInterface $request, ResponseInterface $response, $args){

    	$cat = \api\Models\Categories::where("id", $args['id'])->get();
        $response = JsonResponse::make($response, $cat);
    }

    public function add(RequestInterface $request, ResponseInterface $response, $body){
        var_dump($body);
        die();
    }
}