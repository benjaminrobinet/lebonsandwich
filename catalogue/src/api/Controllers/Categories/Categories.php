<?php

namespace api\Controllers\Categories;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2018-12-13
 * Time: 08:50
 */

class Categories{
    public function __invoke(RequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode(['success' => true]));

        return $response;
    }
}