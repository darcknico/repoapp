<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioTipo extends Model{
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
    //protected $primaryKey = 'id';

	protected $table = 'usuario_tipo';

	protected $fillable = [
		'nombre'
	];

    public function setContraseña($password){
        $this->updated([
            'contraseniaUsuario' => password_hash($password, PASSWORD_DEFAULT)
            ]);
    }

    public function usuarios(){
        return $this->hasMany('App\Models\ListaVenta','idUsuario','idUsuarioTipo')->orderBy('creado','asc');
    }
}