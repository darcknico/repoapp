<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaVentaProducto extends Model{
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

    
	protected $table = 'listaventa_producto';

	protected $fillable = [
		'idListaVenta',
        'idProducto',
        'cantidad'
	];

    public function producto(){
        return $this->belongsTo('App\Models\Producto','idProducto');
    }
    public function listaVenta(){
        return $this->belongsTo('App\Models\ListaVenta','idListaVenta');
    }

    public function precio(){
        return $this->producto->precioVenta();
    }
    public function subtotal()
    {
        return $this->attributes['cantidad'] * $this->producto->precioVenta();
    }
}