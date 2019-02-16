<?php
namespace api\Models;
use Illuminate\Database\Eloquent\Model;

class Sandwich extends Model
{
    protected
		$table = "sandwich", //Nom de la table
		$primaryKey  = "id"; //Nom de la clé primaire


	public 
		$timestamps = false; 

	public function categorie(){
        return $this->belongsToMany('api\Models\Categorie', 'sand2cat', 'sand_id', 'cat_id');
    }
}
