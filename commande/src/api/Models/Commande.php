<?php
namespace api\Models;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model{
    protected
		$table = "commande", //Nom de la table
		$primaryKey  = "id", //Nom de la clé primaire
		$casts = ['id' => 'string']; //Caster la valeur de la clé primaire en string

	public function items(){
	    return $this->hasMany('api\Models\Item', 'command_id');
    }
}