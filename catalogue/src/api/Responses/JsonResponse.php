<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public static function make(ResponseInterface $response, $data ,$code = 200, $local = "fr-FR"){
        
		$resp = [
			"type" => (count($data) > 1) ? "collection" : "resource",
			"count" => count($data),
			"locale" => $local,
			"data" => $data
		];

        return $response->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($resp));
    }
}