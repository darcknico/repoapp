<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProveedorProducto extends Model{
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

    
	protected $table = 'proveedor_producto';

	protected $fillable = [
        'idProducto',
        'idProveedor'
	];

    public function producto(){
        return $this->belongsTo('App\Models\Producto','idProducto');
    }
    public function proveedor(){
        return $this->belongsTo('App\Models\ListaCompra','idProveedor');
    }

}