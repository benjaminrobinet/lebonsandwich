<?php

namespace scripts;

use Slim\Container;
use GuzzleHttp\Client;

class RegisterServices
{
    public static function register(Container $c, $conf){
        $c["catalogue"] = function($c) use ($conf) {
        	return new Client([
        		'base_uri' => $conf["catalogue_host"],
        		"timeout" => 2
        	]);
        };
    }
}