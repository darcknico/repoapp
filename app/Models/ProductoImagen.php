<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model{
	/**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'creado';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modificado';

    
	protected $table = 'producto_imagen';

	protected $fillable = [
        'idProducto',
		'direccion',
        'optimizado'
	];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto','idProducto');
    }

}