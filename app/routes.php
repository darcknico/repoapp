<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/','HomeController:index')->setName('home');

$app->group('', function () {

	$this->get('/auth/signup','AuthController:getSignup')->setName('auth.signup');
	$this->post('/auth/signup','AuthController:postSignup');

	$this->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin','AuthController:postSignIn');

})->add(new GuestMiddleware($container));

$app->group('',function () {

	$this->get('/auth/signout','AuthController:getSignOut')->setName('auth.signout');

	$this->get('/auth/password/change','PasswordController:getChangePassword')->setName('auth.password.change');
	$this->post('/auth/password/change','PasswordController:postChangePassword');

})->add(new AuthMiddleware($container));

$app->get('/marca','MarcaControlador:listAll')->setName('almacen.marca.list');
$app->get('/marca/new','MarcaControlador:getFirst')->setName('almacen.marca.new');
$app->post('/marca','MarcaControlador:post')->setName('almacen.marca');
$app->get('/marca/{id}','MarcaControlador:get')->setName('almacen.marca.edit');
$app->post('/marca/{id}','MarcaControlador:edit')->setName('almacen.marca.edit');
$app->get('/marca/delete/{id}','MarcaControlador:delete')->setName('almacen.marca.delete');


$app->get('/producto','ProductoControlador:listAll')->setName('almacen.producto.list');
$app->get('/producto/new','ProductoControlador:getFirst')->setName('almacen.producto.new');
$app->post('/producto','ProductoControlador:post')->setName('almacen.producto');
$app->get('/producto/{id}','ProductoControlador:get')->setName('almacen.producto.edit');
$app->post('/producto/{id}','ProductoControlador:edit')->setName('almacen.producto.edit');
$app->get('/producto/delete/{id}','ProductoControlador:delete')->setName('almacen.producto.delete');

$app->get('/producto/{idProducto}/preciosventa','PrecioVentaControlador:listAll')->setName('almacen.producto.precioventa');
$app->post('/producto/{idProducto}/preciosventa','PrecioVentaControlador:post')->setName('almacen.producto.precioventa');
$app->get('/producto/{idProducto}/preciosventa/{id}','PrecioVentaControlador:delete')->setName('almacen.producto.precioventa.delete');
$app->get('/producto/{idProducto}/imagen','ProductoControlador:newImagen')->setName('almacen.producto.imagen');
$app->post('/producto/{idProducto}/imagen','ProductoControlador:postImagen')->setName('almacen.producto.imagen');
$app->get('/producto/{idProducto}/imagen/{id}','ProductoControlador:deleteImagen')->setName('almacen.producto.imagen.delete');

$app->get('/listaventa','ListaVentaControlador:listAll')->setName('almacen.listaventa.list');
$app->get('/listaventa/new','ListaVentaControlador:getFirst')->setName('almacen.listaventa.new');
$app->post('/listaventa','ListaVentaControlador:post')->setName('almacen.listaventa');
$app->get('/listaventa/{id}','ListaVentaControlador:get')->setName('almacen.listaventa.edit');
$app->post('/listaventa/{id}','ListaVentaControlador:edit')->setName('almacen.listaventa.edit');
$app->get('/listaventa/{id}/delete','ListaVentaControlador:delete')->setName('almacen.listaventa.delete');
$app->get('/listaventa/{idListaVenta}/producto','ListaVentaControlador:newProducto')->setName('almacen.listaventa.producto.new');
$app->post('/listaventa/{idListaVenta}/producto','ListaVentaControlador:postProducto')->setName('almacen.listaventa.producto');
$app->get('/listaventa/{idListaVenta}/producto/{id}','ListaVentaControlador:getProducto')->setName('almacen.listaventa.producto.get');
$app->post('/listaventa/{idListaVenta}/producto/{id}/edit','ListaVentaControlador:editProducto')->setName('almacen.listaventa.producto.edit');
$app->get('/listaventa/{idListaVenta}/producto/{id}/delete','ListaVentaControlador:deleteProducto')->setName('almacen.listaventa.producto.delete');

$app->get('/proveedor','ProveedorControlador:listAll')->setName('almacen.proveedor.list');
$app->get('/proveedor/new','ProveedorControlador:getFirst')->setName('almacen.proveedor.new');
$app->post('/proveedor/new','ProveedorControlador:post')->setName('almacen.proveedor.new');
$app->get('/proveedor/{id}','ProveedorControlador:get')->setName('almacen.proveedor.edit');
$app->post('/proveedor/{id}','ProveedorControlador:edit')->setName('almacen.proveedor.edit');
$app->get('/proveedor/{id}/delete','ProveedorControlador:delete')->setName('almacen.proveedor.delete');
$app->get('/proveedor/{idProveedor}/producto','ProveedorControlador:listProducto')->setName('almacen.proveedor.producto.list');
$app->post('/proveedor/{idProveedor}/producto','ProveedorControlador:postProducto')->setName('almacen.proveedor.producto.post');
$app->get('/proveedor/{idProveedor}/producto/{id}','ProveedorControlador:deleteProducto')->setName('almacen.proveedor.producto.delete');

$app->get('/proveedor/{idProveedor}/producto/{idProducto}/precio','ProveedorControlador:listProductoPrecio')->setName('almacen.proveedor.producto.precio.list');
$app->post('/proveedor/{idProveedor}/producto/{idProducto}/precio','ProveedorControlador:postProductoPrecio')->setName('almacen.proveedor.producto.precio.post');
$app->get('/proveedor/{idProveedor}/producto/{idProducto}/precio/{id}','ProveedorControlador:deleteProductoPrecio')->setName('almacen.proveedor.producto.precio.delete');

$app->get('/listacompra/','ListaCompraControlador:listAll')->setName('almacen.listacompra.list');
$app->get('/listacompra/new','ListaCompraControlador:getFirst')->setName('almacen.listacompra.new');
$app->post('/listacompra/new','ListaCompraControlador:post')->setName('almacen.listacompra.new');
$app->get('/listacompra/{id}/edit','ListaCompraControlador:get')->setName('almacen.listacompra.edit');
$app->post('/listacompra/{id}/edit','ListaCompraControlador:edit')->setName('almacen.listacompra.edit');
$app->get('/listacompra/{id}/delete','ListaCompraControlador:delete')->setName('almacen.listacompra.delete');
$app->get('/listacompra/{idListaCompra}/detalle','ListaCompraControlador:newDetalle')->setName('almacen.listacompra.detalle.new');
$app->get('/listacompra/{idListaCompra}/producto/[{id}]','ListaCompraControlador:getProducto')->setName('almacen.listacompra.producto');
$app->get('/listacompra/{idListaCompra}/proveedor/[{id}]','ListaCompraControlador:getProveedor')->setName('almacen.listacompra.proveedor');
$app->post('/listacompra/{idListaCompra}/detalle/','ListaCompraControlador:postDetalle')->setName('almacen.listacompra.detalle.post');
$app->get('/listacompra/{idListaCompra}/detalle/{id}','ListaCompraControlador:getDetalle')->setName('almacen.listacompra.detalle.edit');
$app->post('/listacompra/{idListaCompra}/detalle/{id}','ListaCompraControlador:editDetalle')->setName('almacen.listacompra.detalle.edit');
$app->get('/listacompra/{idListaCompra}/detalle/{id}/delete','ListaCompraControlador:deleteDetalle')->setName('almacen.listacompra.detalle.delete');

$app->get('/usuariotipo','UsuarioTipoControlador:getList');
$app->get('/usuariotipo/{id}','UsuarioTipoControlador:get');
$app->post('/usuariotipo/','UsuarioTipoControlador:post');
$app->delete('/usuariotipo/{id}','UsuarioTipoControlador:delete');
$app->put('/usuariotipo/{id}','UsuarioTipoControlador:put');