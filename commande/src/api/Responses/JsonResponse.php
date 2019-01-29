<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public static function make(ResponseInterface $response, $data ,$count, $pagination = null, $code = 200, $local = "fr-FR"){
        
		$resp = [
			"type" => ($count > 1) ? "collection" : "resource",
			"count" => $count,
			"size" => ($count != count($data)) ? count($data) : null,
			"locale" => $local,
			"links" => $pagination,
			"data" => $data
		];

		$resp = array_filter($resp);

        return $response->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($resp));
    }
}