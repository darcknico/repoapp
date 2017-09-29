<?php

namespace App\Controllers\Almacen;

use App\Models\Marca;
use App\Models\Producto;
use App\Models\ListaVenta;
use App\Models\ListaVentaProducto;
use App\Models\Usuario;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class ListaVentaControlador extends Controller{

	public function listAll($request,$response,$args)
	{
		$list = ListaVenta::where('idUsuario',$this->auth->user()->id)->get();

		return $this->view->render($response,'almacen/listaventa/list.twig',['data'=>$list]);
	}

	public function get($request,$response,$args)
	{
		$old = ListaVenta::where('id',$args['id'])->first();
		$data = $old->productos;

		return $this->view->render($response,'almacen/listaventa/edit.twig',['old'=>$old, 'data'=>$data]);
	}

	public function getFirst($request,$response,$args)
	{
		return $this->view->render($response,'almacen/listaventa/edit.twig');
	}
	
	public function post($request,$response,$args)
	{

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listaventa.new'));
		}

		ListaVenta::create([
			'nombre' => $request->getParam('nombre'),
			'idUsuario' => $this->auth->user()->id
			]);

		$this->flash->addMessage('info', 'Creacion de la Lista de Compras con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listaventa.list'));
	}

	public function edit($request,$response,$args)
	{
		$old = ListaVenta::where('id',$args['id'])->first();

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listaventa.edit',['id'=>$args['id']]));
		}

		$old->update([
			'nombre' => $request->getParam('nombre')
			]);

		$this->flash->addMessage('info', 'Modificacion de la lista de Compras con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listaventa.list'));
	}

	public function delete($request,$response,$args)
	{
		$old = ListaVenta::where('id',$args['id'])->first();
		$old->delete();

		return $response->withRedirect($this->router->pathFor('almacen.listaventa.list'));
	}

	public function newProducto($request,$response,$args)
	{
		$data = Producto::all();
		return $this->view->render($response,'almacen/listaventa/producto.twig',['data'=>$data,'idListaVenta'=>$args['idListaVenta']]);
	}

	public function postProducto($request,$response,$args)
	{
		$validation = $this->validator->validate($request, [
			'cantidad' => v::notEmpty()->positive()->intVal(),
			'idProducto' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listaventa.producto.new',[ 'idListaVenta'=>$args['idListaVenta'] ] ));
		}

		ListaVentaProducto::create([
			'cantidad' => $request->getParam('cantidad'),
			'idProducto' => $request->getParam('idProducto'),
			'idListaVenta' => $args['idListaVenta']
			]);

		$this->flash->addMessage('info', 'Creacion del detalle con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listaventa.edit',[ 'id' => $args['idListaVenta'] ] ));
	}

	public function getProducto($request,$response,$args)
	{
		$data = Producto::all();
		$old = ListaVentaProducto::where('id',$args['id'])->first();
		return $this->view->render($response,'almacen/listaventa/producto.twig',['old'=>$old, 'data'=>$data,'idListaVenta'=>$args['idListaVenta']]);
	}

	public function editProducto($request,$response,$args)
	{
		$old = ListaVentaProducto::where('id',$args['id'])->first();

		$validation = $this->validator->validate($request, [
			'cantidad' => v::notEmpty()->positive()->intVal(),
			'idProducto' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listaventa.producto',[ 'idListaVenta'=>$args['idListaVenta'], 'id'=>$args['id']]));
		}

		$old->update([
			'cantidad' => $request->getParam('cantidad'),
			'idProducto' => $request->getParam('idProducto')
			]);

		$this->flash->addMessage('info', 'Modificacion del detalle con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listaventa.edit',[ 'id'=>$args['idListaVenta']] ));
	}

	public function deleteProducto($request,$response,$args)
	{
		$old = ListaVentaProducto::where('id',$args['id'])->first();
		$old->delete();

		return $response->withRedirect($this->router->pathFor('almacen.listaventa.edit',[ 'id'=>$args['idListaVenta']] ));
	}
}