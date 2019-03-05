<?php
/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2019-02-26
 * Time: 16:14
 */

namespace api\Middlewares;


use api\Errors\JsonError;
use api\Errors\JsonNotFound;
use api\Models\Commande;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthorizationToken
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {

        $token = false;
        if(!empty($request->getHeader('X-lbs-token'))){
            $token = $request->getHeader('X-lbs-token')[0];
        } else if(!empty($request->getQueryParam('token'))){
            $token = $request->getQueryParam('token');
        }

        if(!$token){
            return JsonError::make($response, 'Bad Request: You have to specify a token.', 400);
        }

        $commande_id = $request->getAttribute('routeInfo')[2]['id'];
        $commande = Commande::find($commande_id);

        if(!$commande){
            $response = JsonNotFound::make($response);
            return $response;
        }

        if($commande->token === $token){
            return $next($request, $response);
        } else {
            return JsonError::make($response, 'Unauthorized: Bad token for specified command.', 401);
        }
    }
}