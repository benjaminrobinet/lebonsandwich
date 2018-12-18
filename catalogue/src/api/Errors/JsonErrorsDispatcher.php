<?php
/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2018-12-18
 * Time: 17:18
 */

namespace api\Errors;

use Slim\Container;

class JsonErrorsDispatcher
{

    public static function dispatch(Container $c){
        $c['errorHandler'] = function ($c) {
            return new JsonErrorHandler();
        };
        $c['notFoundHandler'] = function ($c) {
            return new JsonNotFound();
        };
        $c['notAllowedHandler'] = function ($c) {
            return new JsonMethodNotAllowed();
        };
    }
}