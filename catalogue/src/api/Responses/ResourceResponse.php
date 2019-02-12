<?php
namespace api\Responses;

use Psr\Http\Message\ResponseInterface;

class ResourceResponse
{
    public static function make(ResponseInterface $response, $data, $pagination = null, $code = 200, $local = "fr-FR"){
        $resp = [
            "type" => "resource",
        ];

        $resp = array_merge($resp, $data);

        if($pagination !== null){
            $resp['links'] = $pagination;
        }

        return JsonResponse::make($response, $resp, $code, $local);
    }
}