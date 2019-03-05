<?php

namespace scripts;

use Slim\Container;
use GuzzleHttp\Client;

class RegisterServices
{
    public static function register(Container $c, $conf){
        /**
         * @param $c
         * @return Client
         */
        $c["catalogue"] = function($c) use ($conf) {
        	return new Client([
        		'base_uri' => $conf["catalogue_host"],
        		'headers' => [
        		    'Content-Type' => 'application/json'
                ],
                'allow_redirects' => true,
        		'timeout' => 2.0,
                'http_errors' => false
        	]);
        };
    }
}