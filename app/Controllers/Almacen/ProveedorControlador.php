<?php

namespace App\Controllers\Almacen;

use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\ProveedorProducto;
use App\Models\PrecioCompra;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class ProveedorControlador extends Controller{

	public function listAll($request,$response)
	{
		$data = Proveedor::all();

		return $this->view->render($response,'almacen/proveedor/list.twig',['data'=>$data]);
	}

	public function getFirst($request,$response)
	{
		return $this->view->render($response,'almacen/proveedor/edit.twig');
	}
	
	public function post($request,$response)
	{

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.proveedor.new'));
		}

		Proveedor::create([
			'nombre' => $request->getParam('nombre'),
			'descripcion' => $request->getParam('descripcion')
			]);

		$this->flash->addMessage('info', 'Creacion del Proveedor con exito');

		return $response->withRedirect($this->router->pathFor('almacen.proveedor.list'));
	}

	public function get($request,$response,$args)
	{
		$old = Proveedor::where('id',$args['id'])->first();

		return $this->view->render($response,'almacen/proveedor/edit.twig',['old'=>$old]);
	}

	public function edit($request,$response,$args)
	{
		$old = Proveedor::where('id',$args['id'])->first();

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.proveedor.edit',[ 'id'=>$args['id']]));
		}

		$old->update([
			'nombre' => $request->getParam('nombre'),
			'descripcion' => $request->getParam('descripcion')
			]);

		$this->flash->addMessage('info', 'Modificacion del Proveedor con exito');

		return $response->withRedirect($this->router->pathFor('almacen.proveedor.list'));
	}

	public function delete($request,$response,$args)
	{
		$old = Proveedor::where('id',$args['id'])->first();
		$old->delete();

		$this->flash->addMessage('info', 'Eliminacion del Proveedor con exito');

		return $response->withRedirect($this->router->pathFor('almacen.proveedor.list'));
	}

	public function listProducto($request,$response,$args)
	{
		$proveedor = Proveedor::where('id',$args['idProveedor'])->first();
		$data = $proveedor->proveedorProductos()->whereIn('idProducto',ProveedorProducto::select('idProducto')->where('idProveedor',$args['idProveedor'])->get())->get();
		$producto = Producto::whereNotIn('id',ProveedorProducto::select('idProducto')->where('idProveedor',$args['idProveedor'])->get())->get();

		return $this->view->render($response,'almacen/proveedor/producto.twig',['proveedor'=>$proveedor,'data'=>$data,'producto'=>$producto]);
	}

	public function postProducto($request,$response,$args)
	{
		$validation = $this->validator->validate($request, [
			'idProducto' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.proveedor.producto.list',['idProveedor'=>$args['idProveedor']]));
		}

		ProveedorProducto::create([
			'idProducto' => $request->getParam('idProducto'),
			'idProveedor' => $args['idProveedor']
			]);

		$this->flash->addMessage('info', 'Asociacion del Proveedor con Producto con exito');

		return $response->withRedirect($this->router->pathFor('almacen.proveedor.producto.list',['idProveedor'=>$args['idProveedor']]));
	}

	public function deleteProducto($request,$response,$args)
	{
		$old = ProveedorProducto::where('id',$args['id'])->first();
		$old->delete();

		$this->flash->addMessage('info', 'Eliminacion del Proveedor con exito');

		return $response->withRedirect($this->router->pathFor('almacen.proveedor.producto.list',['idProveedor'=>$args['idProveedor']]));
	}

	public function listProductoPrecio($request,$response,$args)
	{
		$proveedor = Proveedor::where('id',$args['idProveedor'])->first();
		$producto = Producto::where('id',$args['idProducto'])->first();
		$data = PrecioCompra::where('idProducto',$args['idProducto'])->where('idProveedor',$args['idProveedor'])->orderBy('creado','desc')->get();

		return $this->view->render($response,'almacen/proveedor/precio.twig',['proveedor'=>$proveedor,'data'=>$data,'producto'=>$producto]);
	}

	public function postProductoPrecio($request,$response,$args)
	{
		$validation = $this->validator->validate($request, [
			'precio' => v::notEmpty()->positive()->floatVal()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.proveedor.producto.precio.list',['idProveedor'=>$args['idProveedor'],'idProducto' => $args['idProducto']]));
		}

		PrecioCompra::create([
			'precio' => $request->getParam('precio'),
			'idProveedor' => $args['idProveedor'],
			'idProducto' => $args['idProducto'],
			'idUsuario' => $this->auth->user()->id
			]);

		$this->flash->addMessage('info', 'Precio para el Producto del Proveedor con exito');

		return $response->withRedirect($this->router->pathFor('almacen.proveedor.producto.precio.list',['idProveedor'=>$args['idProveedor'],'idProducto' => $args['idProducto']]));
	}

	public function deleteProductoPrecio($request,$response,$args)
	{
		$old = PrecioCompra::where('id',$args['id'])->first();
		$old = $old->where('idUsuario',$this->auth->user()->id)->first();
		if(!$old){
			$this->flash->addMessage('info', 'Solo puede eliminar los precios que usted agrego');
			return $response->withRedirect($this->router->pathFor('almacen.proveedor.producto.precio.list',['idProveedor'=>$args['idProveedor'],'idProducto' => $args['idProducto']]));
		}
		$old->delete();

		$this->flash->addMessage('info', 'Eliminacion del precio para el Producto del Proveedor con exito');

		return $response->withRedirect($this->router->pathFor('almacen.proveedor.producto.precio.list',['idProveedor'=>$args['idProveedor'],'idProducto' => $args['idProducto']]));
	}
}