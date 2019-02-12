<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    private static $response = [];

    public static function make(ResponseInterface $response, $data, $code = 200, $local = "fr-FR"){
        self::$response = [
            "locale" => $local,
        ];
        self::$response = array_merge(self::$response, $data);

        return $response->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(self::$response));
    }
}