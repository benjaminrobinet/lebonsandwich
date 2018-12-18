<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public static function make(ResponseInterface $response, $data, $code = 200){
        $data = (is_array($data) ? json_encode($data) : (is_string($data) && is_array(json_decode($data)) ? $data : json_encode(['Something went wrong'])));
        return $response->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->write($data);
    }
}