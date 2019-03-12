<?php
namespace api\Models;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model{
    protected
		$table = "client", //Nom de la table
		$primaryKey  = "id", //Nom de la clé primaire
		$casts = ['id' => 'string']; //Caster la valeur de la clé primaire en string

    public $incrementing = false;
}