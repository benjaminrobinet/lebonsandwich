<?php

namespace scripts;
use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public static function init($conf){
        $capsule = new Capsule();

        $capsule->addConnection($conf);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}