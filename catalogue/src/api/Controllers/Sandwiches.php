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

class Sandwiches{
	protected $container;

	public function single(RequestInterface $request, ResponseInterface $response, $args){

	}
}