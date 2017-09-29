<?php

namespace App\Controllers\Auth;

use App\Models\Usuario;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller{

	public function getSignOut($request,$response)
	{
		$this->auth->logout();

		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getSignIn($request,$response)
	{
		return $this->view->render($response,'auth/signin.twig');
	}

	public function postSignIn($request,$response)
	{
		$auth = $this->auth->attempt(
			$request->getParam('correo'),
			$request->getParam('contraseña')
			);
		if(!$auth){

			$this->flash->addMessage('error', 'No se a podido acceder');

			return $response->withRedirect($this->router->pathFor('auth.signin'));
		}

		return $response->withRedirect($this->router->pathFor('home'));
	}


	public function getSignup($request,$response
		){
		
		return $this->view->render($response, 'auth/signup.twig');
	}

	public function postSignup($request,$response)
	{

		$validation = $this->validator->validate($request, [
			'nombre' => v::noWhitespace()->notEmpty()->alpha(),
			'correo' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
			'contraseña' => v::noWhitespace()->notEmpty()
			]);


		if($validation->failed() ){
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		$user = Usuario::create([
			'nombre' => $request->getParam('nombre'),
			'correo' => $request->getParam('correo'),
			'contraseña' => password_hash($request->getParam('contraseña'),PASSWORD_DEFAULT),
			]);

		$this->flash->addMessage('info', 'Creacion de la cuenta con exito');

		$this->auth->attempt($user->correoUsuario,$request->getParam('contraseña'));

		return $response->withRedirect($this->router->pathFor('home'));
	}
}