<?php

namespace App\Controllers;

use \Slim\Views\Twig as View;

class HomeController extends Controller{

	public function index($request, $response) {
		/*
		$user = Usuario::where('identificadorUsuario',1)->first();
		var_dump($user->correoUsuario);
		die();
		Usuario::create([
			'nombreUsuario' => 'Ricardo',
			'correoUsuario' => 'Ricardo@GMAIL.com',
			'contraseniaUsuario' => 'asdasd'
			]);
			*/
		return $this->view->render($response,'home.twig');
	}
}