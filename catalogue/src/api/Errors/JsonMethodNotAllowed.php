<?php
namespace api\Errors;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class JsonMethodNotAllowed
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $methods){
        $response = $response->withHeader('Allow', implode(', ', $methods));

        $response = JsonError::make(
            $response,
            'Method not allowed',
            405);

        return $response;
    }
}