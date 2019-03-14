<?php

namespace api\Middlewares;


use api\Errors\JsonError;
use api\Errors\JsonNotFound;
use api\Models\Clients;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException ;
use Firebase\JWT\BeforeValidException;

class Authorization
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        if(!empty($request->getHeader('Authorization'))){
            //Récupération de l'id de l'utilisateur
            $user_id = $request->getAttribute('routeInfo')[2]['id'];
            $method = $request->getAttribute('routeInfo')["request"][0];
            
            //Si l'id de l'user est présent dans l'url
            if(!empty($user_id)){
                //Var init 
                $secret = "BenjaminHugo";

                //Récupérer l'entete de la requête
                $auth = $request->getHeader('Authorization')[0];
                $content = explode(" ",$auth);


                if($content[0] === 'Basic' && $method === "POST")
                {
                    if($content[1] === "bWljaGVsOm1pY2hlbA=="){
                        return $next($request, $response);
                    }else{
                        return JsonError::make($response, "Bad Request: Header Authorization invalid");
                    }
                }else if($content[0] === 'Bearer' && $method === "GET"){
                    try{
                        //Verification que le token appartient bein au client
                        $token = JWT::decode($content[1], $secret, ['HS512']);
                        if($token->uid === $user_id){
                            return $next($request, $response);
                        }else{
                            return JsonError::make($response, "Bad Request: Token invalid", 400);
                        }
                    }catch (ExpiredException $e) 
                    {
                        return JsonError::make($response, 'Unauthorized: Token expired', 401);
                    } 
                    catch (SignatureInvalidException $e) 
                    {
                        return JsonError::make($response, 'Unauthorized: Signature invalid', 401);
                    } 
                    catch (BeforeValidException $e) 
                    {
                        return JsonError::make($response, 'Unauthorized: Token invalid', 401);
                    } 
                    catch (\UnexpectedValueException $e) 
                    {
                        return JsonError::make($response, 'Unauthorized: Token invalid', 401);
                    }
                }else{
                    return JsonError::make($response, "Bad Request", 400);
                }
            }else{
                return JsonError::make($response, "Bad Request: No User id present");
            }
        }else{
            return JsonError::make($response, 'Bad Request: No Authorization Header present.', 400);
        }
    }
}