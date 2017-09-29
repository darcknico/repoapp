<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model{
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
    
	protected $table = 'proveedores';

	protected $fillable = [
		'nombre',
        'descripcion'
	];

    public function listasCompra(){
        return $this->hasMany('App\Models\ListaCompraProducto','idProveedor','id');
    }

    public function proveedorProductos(){
        return $this->hasMany('App\Models\ProveedorProducto','idProveedor','id');
    }

    public function preciosCompra(){
        return $this->hasMany('App\Models\PrecioCompra','idProveedor','id')->orderBy('creado','desc');
    }
}