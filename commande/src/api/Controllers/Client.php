<?php
/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2019-03-05
 * Time: 17:21
 */

namespace api\Controllers;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function auth(RequestInterface $request, ResponseInterface $response, $args){
        $id = $args['id'];
    }
}