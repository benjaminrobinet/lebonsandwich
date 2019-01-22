<?php
namespace api\Models;


class Categories
{
    protected
		$table = "categorie", //Nom de la table
		$primaryKey  = "id"; //Nom de la clé primaire

	public 
		$timestamps = false; 

	public function sandwich(){
        return $this->belongsToMany('api\Models\Sandwich', 'sand2cat', 'cat_id', 'sand_id');
    }
}