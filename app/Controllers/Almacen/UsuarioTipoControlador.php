<?php

namespace App\Controllers\Almacen;

use App\Models\Usuario;
use App\Models\UsuarioTipo;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class UsuarioTipoControlador extends Controller{

	public function getList($request,$response)
	{
		$todos = UsuarioTipo::all();
		return $response->withJson($todos);
	}

	public function get($request,$response,$args)
	{
		try {
			$todos = UsuarioTipo::where('id',$args['id'])->get();
			return $response->withJson($todos);
		} catch (\Illuminate\Database\QueryException $e) {
			return $response->withJson($e);
		} catch (\Exception $e) {
			return $response->withJson($e);
		}
	}

	public function post($request,$response)
	{
		try{
			$input = $request->getParsedBody();
			$todos = UsuarioTipo::create([
				'nombre'=>$input['nombre']
				]);
			return $response->withJson($todos);
		} catch (\Illuminate\Database\QueryException $e) {
			return $response->withJson($e);
		} catch (\Exception $e) {
			return $response->withJson($e);
		}
	}

	public function delete($request,$response,$args)
	{
		
	}

	public function put($request,$response,$args)
	{
		
	}
}