<?php

namespace App\Controllers\Almacen;

use App\Models\Marca;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class MarcaControlador extends Controller{

	public function listAll($request,$response)
	{
		$marcas = Marca::all();

		return $this->view->render($response,'almacen/marca/list.twig',['data'=>$marcas]);
	}

	public function get($request,$response,$args)
	{
		$marca = Marca::where('id',$args['id'])->first();

		return $this->view->render($response,'almacen/marca/edit.twig',['old'=>$marca]);
	}

	public function getFirst($request,$response)
	{
		return $this->view->render($response,'almacen/marca/edit.twig');
	}
	
	public function post($request,$response)
	{

		$validation = $this->validator->validate($request, [
			'nombre' => v::noWhitespace()->notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.marca.new'));
		}

		$marca = Marca::create([
			'nombre' => $request->getParam('nombre'),
			'descripcion' => $request->getParam('descripcion')
			]);

		$this->flash->addMessage('info', 'Creacion de la marca con exito');

		return $response->withRedirect($this->router->pathFor('almacen.marca.list'));
	}

	public function edit($request,$response,$args)
	{
		$id = $args['id'];
		$marca = Marca::where('id',$args['id'])->first();

		$validation = $this->validator->validate($request, [
			'nombre' => v::noWhitespace()->notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.marca')."/edit/".$id);
		}

		$marca->update([
			'nombre' => $request->getParam('nombre'),
			'descripcion' => $request->getParam('descripcion')
			]);

		$this->flash->addMessage('info', 'Modificacion de la marca con exito');

		return $response->withRedirect($this->router->pathFor('almacen.marca.list'));
	}

	public function delete($request,$response,$args)
	{
		$marca = Marca::where('id',$args['id'])->first();
		$marca->delete();

		return $response->withRedirect($this->router->pathFor('almacen.marca.list'));
	}
}