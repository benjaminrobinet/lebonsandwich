<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class CollectionResponse
{
    public static function make(ResponseInterface $response, $data, $links = null, $code = 200, $local = "fr-FR"){

        $resp = [
            "type" => "collection",
            "count" => array_values($data)[0]->count(),
            "size" => count(array_values($data)[0]),
        ];

        $resp = array_merge($resp, $data);

        if($links !== null){
            $resp['links'] = $links;
        }

        return JsonResponse::make($response, $resp, $code, $local);
    }
}