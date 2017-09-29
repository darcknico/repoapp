<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model{
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
    protected $primaryKey = 'id';

	protected $table = 'usuarios';

	protected $fillable = [
		'nombre',
		'correo',
		'contraseña',
	];

    public function setContraseña($password){
        $this->updated([
            'contraseña' => password_hash($password, PASSWORD_DEFAULT)
            ]);
    }

    public function listasVenta(){
        return $this->hasMany('App\Models\ListaVenta','idUsuario','id')->orderBy('creado','asc');
    }
    public function listasCompra(){
        return $this->hasMany('App\Models\ListaCompra','idUsuario','id')->orderBy('creado','asc');
    }

    public function preciosCompraProducto(){
        return $this->hasMany('App\Models\PrecioCompra','idUsuario','id')->orderBy('creado','asc');
    }
}