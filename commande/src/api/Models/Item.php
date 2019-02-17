<?php
/**
 * Created by PhpStorm.
 * User: benjaminrobinet
 * Date: 2019-01-15
 * Time: 17:21
 */

namespace api\Models;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected
        $table = "item", //Nom de la table
        $primaryKey  = "id"; // Nom de la clé primaire

    public
        $timestamps = false;

    public function commande(){
        return $this->hasOne('api\Models\Commande');
    }
}