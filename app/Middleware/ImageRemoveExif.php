<?php

namespace App\Middleware;

class ImageRemoveExif extends Middleware{
	
	protected $allowedFiles = ['image/jpeg','image/png'];

	public function __invoke($request, $response, $next)
	{		

		$files = $request->getUploadedFiles();
		if (isset( $files['file'])) {
			$newfile = $files['file'];
			$newfile_type = $newfile->getClientMediaType();
			$uploadFilename = $newfile->getClientFilename();
			$newfile->moveTo("assets/almacen/raw/$uploadFilename");
			$pngfile = "assets/almacen/".substr($uploadFilename, 0, -4).".png";

			if('image/jpeg' == $newfile_type){
				$_img = imagecreatefromjpeg("assets/almacen/raw/".$uploadFilename);
				imagepng($_img, $pngfile);
			} elseif ('image/png'){
				copy("assets/almacen/raw/$uploadFilename",$pngfile);
			}

			$request->withAttribute('png_filename',$pngfile);
			//$newfile->close();
		}
		$response = $next($request,$response);

		return $response;
	}
}