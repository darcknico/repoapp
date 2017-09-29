<?php

namespace App\Middleware;

use Aws\S3\S3Client;

class FileMove extends Middleware{
	
	protected $allowedFiles = ['image/jpeg','image/png'];

	public function __invoke($request, $response, $next)
	{		
		
		$files = $request->getUploadedFiles();
		if(isset($files['file'])){
			$s3 = new S3Client(['version'=>'latest','region'=>'us-west-2']);
			$newfile = $files['file'];
			$uploadFileName = $newfile->getClientFilename();
			$pngfile = "assets/almacen/".substr($uploadFileName, 0, -4).".png";

			try {
				$s3->putObject([
					'Bucket'=>'my-bucket',
					'Key'=>'my-object',
					'Body'=> fopen($pngfile,'w'),
					'ACL'=>'public-read'
					]);
			} catch (Exception $e) {
				return $response->withStatus(400);
			}
		}
		$response = $next($request,$response);

		return $response;
	}
}