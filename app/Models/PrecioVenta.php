<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioVenta extends Model{
	public $timestamps = false;
    
	protected $table = 'preciosventa';

	protected $fillable = [
		'idProducto',
        'precio',
        'creado'
	];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto','idProducto');
    }
}