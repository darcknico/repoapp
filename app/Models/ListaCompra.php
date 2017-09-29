<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaCompra extends Model{
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

    
	protected $table = 'listascompra';

	protected $fillable = [
		'nombre',
        'idUsuario'
	];

    public function productos(){
        return $this->hasMany('App\Models\ListaCompraProducto','idListaCompra','id')->orderBy('creado','desc');
    }

    public function usuario(){
        return $this->belongsTo('App\Models\Usuario','idUsuario');
    }

    public function total(){
        $detalles = $this->productos;
        $suma = 0;
        foreach ($detalles as $detalle) {
            $suma = $suma + $detalle->subtotal();

        }
        return $suma;
    }
}