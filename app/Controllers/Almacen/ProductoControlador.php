<?php

namespace App\Controllers\Almacen;

use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagen;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use Psr\Http\Message\UploadedFileInterface as Files;
use Intervention\Image\ImageManagerStatic as Image;


class ProductoControlador extends Controller{

	public function listAll($request,$response)
	{
		$productos = Producto::all();

		return $this->view->render($response,'almacen/producto/list.twig',['data'=>$productos]);
	}

	public function get($request,$response,$args)
	{
		$producto = Producto::where('id',$args['id'])->first();
		$marca = Marca::all();
		$imagenes = $producto->imagenes()->get();
		return $this->view->render($response,'almacen/producto/edit.twig',['old'=>$producto,'data'=>$marca,'imagenes'=>$imagenes]);
	}

	public function getFirst($request,$response)
	{
		$marca = Marca::all();
		return $this->view->render($response,'almacen/producto/edit.twig',['data'=>$marca]);
	}
	
	public function post($request,$response)
	{

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty(),
			'idMarca' => v::notEmpty(),
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.producto.new'));
		}

		$producto = Producto::create([
			'nombre' => $request->getParam('nombre'),
			'descripcion' => $request->getParam('descripcion'),
			'idMarca' => $request->getParam('idMarca')
			]);

		$this->flash->addMessage('info', 'Creacion del producto con exito');

		return $response->withRedirect($this->router->pathFor('almacen.producto.list'));
	}

	public function edit($request,$response,$args)
	{
		$id = $args['id'];
		$producto = Producto::where('id',$args['id'])->first();

		$validation = $this->validator->validate($request, [
			'nombre' => v::notEmpty(),
			'idMarca' => v::notEmpty(),
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('almacen.producto')."/edit/".$id);
		}

		$producto->update([
			'nombre' => $request->getParam('nombre'),
			'descripcion' => $request->getParam('descripcion'),
			'idMarca' => $request->getParam('idMarca')
			]);

		$this->flash->addMessage('info', 'Modificacion del producto con exito');

		return $response->withRedirect($this->router->pathFor('almacen.producto.list'));
	}

	public function delete($request,$response,$args)
	{
		$producto = Producto::where('id',$args['id'])->first();
		$producto->delete();

		return $response->withRedirect($this->router->pathFor('almacen.producto.list'));
	}

	public function newImagen($request,$response,$args){
		$producto = Producto::where('id',$args['idProducto'])->first();
		return $this->view->render($response,'almacen/producto/imagen.twig',['producto'=>$producto]);
	}

	public function postImagen($request,$response,$args)
	{
		$imagepath = 'assets/almacen/producto/'.uniqid('img_').'.png';
		$optpath = 'assets/almacen/producto/'.uniqid('img_').'-320x240.png';
		$file = $request->getUploadedFiles();
		$newfile = $file['file'];
		if($newfile->getError() === UPLOAD_ERR_OK ) {
			$uploadFilename = $newfile->getClientFilename();
			$newfile_type = $newfile->getClientMediaType();
			$newfile->moveTo("assets/almacen/producto/raw/". $uploadFilename);
			$pngfile = "assets/almacen/producto/raw/".substr($uploadFilename, 0, -4).".png";
			if('image/jpeg' == $newfile_type){
				$_img = imagecreatefromjpeg("assets/almacen/producto/raw/".$uploadFilename);
				imagepng($_img, $pngfile);
				unlink("assets/almacen/producto/raw/". $uploadFilename);
			}
			copy($pngfile,$imagepath);
			unlink($pngfile);
			//$imagepath = "assets/almacen/producto/".$uploadFilename;
			$image = Image::make($imagepath);
			$image->resize(320,240);
			$image->save($optpath);
		}

		ProductoImagen::create([
			'idProducto' => $args['idProducto'],
			'direccion' => $imagepath,
			'optimizado' =>$optpath
			]);

		return $response->withRedirect($this->router->pathFor('almacen.producto.edit',['id'=>$args['idProducto']]));
	}

	public function deleteImagen($request,$response,$args)
	{
		$old = ProductoImagen::where('id',$args['id'])->first();
		unlink($old->direccion);
		unlink($old->optimizado);
		$old->delete();

		return $response->withRedirect($this->router->pathFor('almacen.producto.edit',['id'=>$args['idProducto']]));
	}
}