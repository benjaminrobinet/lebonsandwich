<?php
namespace api\Errors;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class JsonNotFound
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response){
        $response = JsonError::make(
            $response,
            "Not Found",
            404
        );

        return $response;
    }

    public static function make(ResponseInterface $response){
        $response = JsonError::make(
            $response,
            "Not Found",
            404
        );

        return $response;
    }
}