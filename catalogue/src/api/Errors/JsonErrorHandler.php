<?php
namespace api\Errors;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class JsonErrorHandler
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Exception $exception){
        $response = JsonError::make(
            $response,
            $exception->getMessage(),
            500
        );

        return $response;
    }
}