<?php
namespace api\Models;
use Illuminate\Database\Eloquent\Model;

class Sandwich extends Model
{
    protected
		$table = "sandwich", //Nom de la table
		$primaryKey  = "id", //Nom de la clé primaire
		$hidden = ["pivot"];

	public 
		$timestamps = false; 

	public function categorie(){
        return $this->belongsToMany('api\Models\Categories', 'sand2cat', 'sand_id', 'cat_id');
    }
}