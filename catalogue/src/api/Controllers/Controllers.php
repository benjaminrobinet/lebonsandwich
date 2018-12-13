<?php
namespace api\Controllers;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controllers
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        $response = $response->withAddedHeader('Content-Type', 'application/json');
        return $next($request, $response);
    }
}