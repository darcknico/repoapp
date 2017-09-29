<?php

namespace App\Controllers\Almacen;

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ListaCompra;
use App\Models\ListaCompraProducto;
use App\Models\PrecioCompra;
use App\Models\Usuario;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class ListaCompraControlador extends Controller{

	public function listAll($request,$response)
	{
		$list = ListaCompra::where('idUsuario',$this->auth->user()->id)->get();

		return $this->view->render($response,'almacen/listacompra/list.twig',['data'=>$list]);
	}

	public function getFirst($request,$response)
	{
		return $this->view->render($response,'almacen/listacompra/edit.twig');
	}
	
	public function post($request,$response)
	{

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listacompra.new'));
		}

		ListaCompra::create([
			'nombre' => $request->getParam('nombre'),
			'idUsuario' => $this->auth->user()->id
			]);

		$this->flash->addMessage('info', 'Creacion de la Lista de Compras con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listacompra.list'));
	}

	public function get($request,$response,$args)
	{
		$old = ListaCompra::where('id',$args['id'])->first();
		$data = $old->productos;

		return $this->view->render($response,'almacen/listacompra/edit.twig',['old'=>$old, 'data'=>$data]);
	}

	public function edit($request,$response,$args)
	{
		$old = ListaCompra::where('id',$args['id'])->first();

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listacompra.edit',['id'=>$args['id']]));
		}

		$old->update([
			'nombre' => $request->getParam('nombre')
			]);

		$this->flash->addMessage('info', 'Modificacion de la lista de Compras con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listacompra.list'));
	}

	public function delete($request,$response,$args)
	{
		$old = ListaCompra::where('id',$args['id'])->first();
		$old->delete();

		return $response->withRedirect($this->router->pathFor('almacen.listacompra.list' ));
	}

	public function newDetalle($request,$response,$args)
	{
		$productos = Producto::all();
		$proveedores = Proveedor::all();
		$listacompra = ListaCompra::where('id',$args['idListaCompra'])->first();
		return $this->view->render($response,'almacen/listacompra/detalle.twig',['productos'=>$productos,'proveedores'=>$proveedores,'listacompra'=>$listacompra]);
	}

	public function getProducto($request,$response,$args)
	{
		$producto = Producto::where('id',$args['id'])->first();
		//var_dump($producto->preciosCompra()->get()->toJson());
		//var_dump($producto->preciosCompra()->select('creado')->groupBy('idProveedor')->get()->toJson());
		//var_dump(PrecioCompra::select(DB::raw('MAX(creado) as creado2'))->where('idProducto',$producto->id)->groupBy('idProveedor')->get()->toJson());
		//$precios = $producto->preciosCompra()->whereRaw('creado IN (select MAX(p.creado) FROM precioscompra AS p WHERE id=p.id GROUP BY p.idProveedor)')->get();
		$precios = $producto->preciosCompra()->whereIn('creado',PrecioCompra::select(DB::raw('MAX(creado) as creado2'))->where('idProducto',$producto->id)->groupBy('idProveedor')->get())->get();
		$listacompra = ListaCompra::where('id',$args['idListaCompra'])->first();
		return $this->view->render($response,'almacen/listacompra/detalle.twig',['producto'=>$producto,'precios'=>$precios,'listacompra'=>$listacompra]);
	}

	public function getProveedor($request,$response,$args)
	{
		$proveedor = Proveedor::where('id',$args['id'])->first();
		$precios = $proveedor->preciosCompra()->whereIn('creado',PrecioCompra::select(DB::raw('MAX(creado) as creado2'))->where('idProveedor',$proveedor->id)->groupBy('idProducto')->get())->get();
		//var_dump($proveedor->preciosCompra()->select('creado')->get()->toJson());
		//var_dump(PrecioCompra::select(DB::raw('MAX(creado) as creado2'))->where('idProveedor',$proveedor->id)->groupBy('idProducto')->get()->toJson());
		$listacompra = ListaCompra::where('id',$args['idListaCompra'])->first();
		return $this->view->render($response,'almacen/listacompra/detalle.twig',['proveedor'=>$proveedor,'precios'=>$precios,'listacompra'=>$listacompra]);
	}

	public function postDetalle($request,$response,$args)
	{
		$validation = $this->validator->validate($request, [
			'cantidad' => v::notEmpty()->positive()->intVal(),
			'idProducto' => v::notEmpty(),
			'idProveedor' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listacompra.detalle.new',['idListaCompra'=>$args['idListaCompra'] ] ));
		}

		ListaCompraProducto::create([
			'cantidad' => $request->getParam('cantidad'),
			'idProducto' => $request->getParam('idProducto'),
			'idProveedor' => $request->getParam('idProveedor'),
			'idListaCompra' => $args['idListaCompra']
			]);

		$this->flash->addMessage('info', 'Creacion del detalle con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listacompra.edit', ['id' => $args['idListaCompra'] ] ));
	}

	public function getDetalle($request,$response,$args)
	{
		$old = ListaCompraProducto::where('id',$args['id'])->first();
		$producto = Producto::where('id',$old->idProducto)->first();
		$precios = $producto->preciosCompra()->whereIn('creado',PrecioCompra::select(DB::raw('MAX(creado) as creado2'))->where('idProducto',$producto->id)->groupBy('idProveedor')->get())->get();
		$listacompra = ListaCompra::where('id',$args['idListaCompra'])->first();
		return $this->view->render($response,'almacen/listacompra/detalle.twig',['producto'=>$producto,'precios'=>$precios,'listacompra'=>$listacompra, 'old'=>$old]);
	}

	public function editDetalle($request,$response,$args)
	{
		$old = ListaCompraProducto::where('id',$args['id'])->first();

		$validation = $this->validator->validate($request, [
			'cantidad' => v::notEmpty()->positive()->intVal(),
			'idProducto' => v::notEmpty(),
			'idProveedor' => v::notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.listacompra.detalle.edit',['idListaCompra'=>$args['idListaCompra'], 'id'=>$args['id']]));
		}

		$old->update([
			'cantidad' => $request->getParam('cantidad'),
			'idProducto' => $request->getParam('idProducto'),
			'idProveedor' => $request->getParam('idProveedor'),
			'idListaCompra' => $args['idListaCompra']
			]);

		$this->flash->addMessage('info', 'Modificacion del detalle con exito');

		return $response->withRedirect($this->router->pathFor('almacen.listacompra.edit',['id'=>$args['idListaCompra']] ));
	}

	public function deleteDetalle($request,$response,$args)
	{
		$old = ListaCompraProducto::where('id',$args['id'])->first();
		$old->delete();

		return $response->withRedirect($this->router->pathFor('almacen.listacompra.edit',['id'=>$args['idListaCompra']] ));
	}
}