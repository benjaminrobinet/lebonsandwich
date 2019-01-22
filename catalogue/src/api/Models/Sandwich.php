<?php
namespace api\Models;


class Sandwich
{
    protected
		$table = "sandwich", //Nom de la table
		$primaryKey  = "id"; //Nom de la clé primaire

	public 
		$timestamps = false; 

	public function categorie(){
        return $this->belongsToMany('api\Models\Categories', 'sand2cat', 'sand_id', 'cat_id');
    }
}