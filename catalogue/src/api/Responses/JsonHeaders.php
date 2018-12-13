<?php

namespace api\Responses;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class JsonHeaders
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        $response = $response->withAddedHeader('Content-Type', 'application/json');
        return $next($request, $response);
    }
}