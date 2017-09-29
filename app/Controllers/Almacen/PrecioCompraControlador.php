<?php

namespace App\Controllers\Almacen;

use App\Models\Marca;
use App\Models\Producto;
use App\Models\PrecioCompra;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PrecioCompraControlador extends Controller{

	public function listAll($request,$response,$args)
	{
		$data = PrecioCompra::all();
		$producto = Producto::where('id',$args['idProducto'])->first();
		$precios = PrecioVenta::where('idProducto',$args['idProducto'])->orderBy('creado','desc')->get()->take(5);
		//$precioVenta = Producto::where('id',$args['idProducto'])->first()->preciosVenta->orderBy('preciosventa.creado')->get();

		return $this->view->render($response,'almacen/producto/precioventa.twig',[ 'producto'=>$producto, 'data'=>$precios, 'idProducto'=>$args['idProducto']]);
	}

	public function post($request,$response,$args)
	{

		$validation = $this->validator->validate($request, [
			'precio' => v::notEmpty()->positive()->floatVal(),
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.producto.precioventa',[
				'idProducto'=>$args['idProducto'] 
				])
			);
		}

		PrecioVenta::create([
			'idProducto' => $args['idProducto'],
			'precio' => $request->getParam('precio'),
			'creado' => date("Y-m-d H:i:s")
			]);

		$this->flash->addMessage('info', 'Creacion del precio con exito');

		return $response->withRedirect($this->router->pathFor('almacen.producto.precioventa',['idProducto'=>$args['idProducto']]));
	}

	public function delete($request,$response,$args)
	{
		$producto = PrecioVenta::where('id',$args['id'])->first();
		$producto->delete();

		return $response->withRedirect($this->router->pathFor('almacen.producto.precioventa',['idProducto'=>$args['idProducto'],'id'=>$args['id']]));
	}
}