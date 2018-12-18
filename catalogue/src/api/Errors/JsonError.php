<?php
/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2018-12-13
 * Time: 10:09
 */

namespace api\Errors;

use api\Responses\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class JsonError
{
    public static function make(ResponseInterface $response, $message, $code){
        $data = [
            'success' => false,
            'message' => $message,
            'code' => $code,
        ];

        return JsonResponse::make($response, $data, $code);
    }
}