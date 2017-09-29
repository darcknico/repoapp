<?php

namespace App\Middleware;

class FileFilter extends Middleware{
	
	protected $allowedFiles = ['image/jpeg','image/png'];

	public function __invoke($request, $response, $next)
	{		

		$files = $request->getUploadedFiles();
		if(isset($files['file'])) {
			$newfile = $files['file'];
			$newfile_type = $newfile->getClientMediaType();

			if(!in_array($newfile_type, $this->allowedFiles)){
				return $response->withStatus(415);
			}
		}
		$response = $next($request,$response);

		return $response;
	}
}