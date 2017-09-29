<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PrecioCompra;

class ListaCompraProducto extends Model{
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

    
	protected $table = 'listacompra_producto';

	protected $fillable = [
		'idListaCompra',
        'idProducto',
        'idProveedor',
        'cantidad'
	];

    public function producto(){
        return $this->belongsTo('App\Models\Producto','idProducto');
    }
    public function listaCompra(){
        return $this->belongsTo('App\Models\ListaCompra','idListaCompra');
    }
    public function proveedor(){
        return $this->belongsTo('App\Models\Proveedor','idProveedor');
    }

    public function precio(){
        $producto = $this->attributes['idProducto'];
        $proveedor = $this->attributes['idProveedor'];
        //var_dump($producto);
        //var_dump($proveedor);
        //var_dump(PrecioCompra::where(['idProducto'=>$producto,'idProveedor'=>$proveedor])->first()->toJson());
        return PrecioCompra::where(['idProducto'=>$producto,'idProveedor'=>$proveedor])->first()->precio;
    }
    public function subtotal()
    {
        return $this->attributes['cantidad'] * $this->precio();
    }
}