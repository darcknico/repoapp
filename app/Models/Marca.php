<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model{
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

    
	protected $table = 'marcas';

	protected $fillable = [
		'nombre',
        'descripcion'
	];

    public function productos(){
        return $this->hasMany('App\Models\Producto','idMarca','id');
    }
}