<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

	protected $fillable = [
        'category_id', 'name', 'type', 'designation', 'position', 'couleur', 'longueur', 'largeur', 'hauteur', 'epaisseur', 'poids', 'prix_uni', 'code_produit', 'code_douanier', 'port_sortie', 'fournisseur', 'image', 'comment'
    ];

    public function category(){
    	return $this->belongsTo('App\Category');
    }
}
