<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioCompra extends Model{
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
    
	protected $table = 'precioscompra';

	protected $fillable = [
		'idProducto',
        'idProveedor',
        'idUsuario',
        'precio'
	];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto','idProducto');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor','idProveedor');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario','idUsuario');
    }
}