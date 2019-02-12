<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class CollectionResponse
{
    public static function make(ResponseInterface $response, $data, $pagination = null, $code = 200, $local = "fr-FR"){

        $resp = [
            "type" => "collection",
            "count" => array_values($data)[0]->count(),
            "size" => count(array_values($data)[0]),
            "links" => $pagination,
        ];

        $resp = array_merge($resp, $data);


        return JsonResponse::make($response, $resp, $code, $local);
    }
}