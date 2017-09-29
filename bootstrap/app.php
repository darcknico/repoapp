<?php

use Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app= new \Slim\App([
	'settings' => [
		'displayErrorDetails'=>true,
	],
	'db' => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'database' => 'almacen',
		'username' => 'alma',
		'password' => 'alma',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => ''
	]
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection(array(
		'driver' => 'mysql',
		'host' => 'localhost',
		'database' => 'almacen',
		'username' => 'alma',
		'password' => 'alma',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => '',
	));

/*
$capsule->addConnection(array(
		'driver' => 'mysql',
		'host' => 'mysql.hostinger.com.ar',
		'database' => 'u488125577_repo',
		'username' => 'u488125577_root',
		'password' => '44165746',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => '',
	));
	*/
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){
	return $capsule;
};

$container['view'] = function($container){
	$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views' , [
		'cache' => false,
		]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
		));

	$view->getEnvironment()->addGlobal('auth', [
		'check' => $container->auth->check(),
		'user' => $container->auth->user()
		]);

	$view->getEnvironment()->addGlobal('flash', $container->flash);

	return $view;
};

$container['validator'] = function ($container) {
	return new App\Validation\Validator;
};

$container['HomeController'] = function ($container) {
	return new \App\Controllers\HomeController($container);
};
$container['AuthController'] = function ($container) {
	return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function ($container) {
	return new \App\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function($container){
	return new \Slim\Csrf\Guard;
};

$container['auth'] = function($container){
	return new \App\Auth\Auth;
};

$container['flash'] = function ($container){
	return new \Slim\Flash\Messages;
};


$container['UsuarioTipoControlador'] = function ($container) {
	return new \App\Controllers\Almacen\UsuarioTipoControlador($container);
};
$container['MarcaControlador'] = function ($container) {
	return new \App\Controllers\Almacen\MarcaControlador($container);
};
$container['ProductoControlador'] = function ($container) {
	return new \App\Controllers\Almacen\ProductoControlador($container);
};
$container['PrecioVentaControlador'] = function ($container) {
	return new \App\Controllers\Almacen\PrecioVentaControlador($container);
};
$container['ListaVentaControlador'] = function ($container) {
	return new \App\Controllers\Almacen\ListaVentaControlador($container);
};
$container['ProveedorControlador'] = function ($container) {
	return new \App\Controllers\Almacen\ProveedorControlador($container);
};
$container['ListaCompraControlador'] = function ($container) {
	return new \App\Controllers\Almacen\ListaCompraControlador($container);
};


$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
//$app->add(new \App\Middleware\FileMove($container));
$app->add(new \App\Middleware\FileFilter($container));
//$app->add(new \App\Middleware\ImageRemoveExif($container));

$app->add($container->csrf );

v::with('App\\Validation\\Rules\\');

require __DIR__ . '/../app/routes.php';