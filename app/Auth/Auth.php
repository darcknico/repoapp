<?php

namespace App\Auth;

use App\Models\Usuario;

/**
* 
*/
class Auth
{

	public function user()
	{
		if (isset($_SESSION['user'])) {
			return Usuario::where('id',$_SESSION['user'])->first();
		} else {
			return false;
		}
	}

	public function check()
	{
		return isset($_SESSION['user']);

	}

	public function attempt($email, $password)
	{
		$user = Usuario::where('correo',$email)->first();

		if(!$user) {
			return false;
		}

		if(password_verify($password, $user->contraseña)) {
			$_SESSION['user'] = $user->id;
			return true;
		}
	}

	public function logout()
	{
		unset($_SESSION['user']);
	}
}