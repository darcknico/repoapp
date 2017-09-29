<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use App\Models\Usuario;
/**
* 
*/
class EmailAvailable extends AbstractRule
{
	
	public function validate($input)
	{
		return Usuario::where('correo', $input)->count()===0;
	}
}