<?php

namespace Support;
use Exception;

class Storage {

	static public function uploadFile($src, $destination) { 

		// extract dir path from destination path, without file name
		$destinationDir = trim(str_replace(basename($destination), '', $destination), '/');

		// concat path
		$path = config('UPLOAD.FILES') . '/' . (! empty($destinationDir) ? $destinationDir . '/' : '');

		// create directory structure if not exists
		if (!is_dir($path)) {
		    mkdir($path, 0755, true);
		}

		// append file name
		$path .= basename($destination);

		self::upload($src, $path);

		return $path;
	}

	static public function uploadImage($src, $destination, $createThumb = false) {

		$filename = basename($destination);
		$destinationDir = trim(str_replace($filename, '', $destination), '/');
		$imagePaths = []; // return array

		// check if file is valid image
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) || !@getimagesize($src)) {
			throw new Exception('File must be an image');
		}

		$imgDir = config('UPLOAD.IMAGES') . '/' . (! empty($destinationDir) ? $destinationDir . '/' : '');
		if (!is_dir($imgDir)) {
		    mkdir($imgDir, 0755, true);
		}

		$imagePath = $imgDir . $filename;

		// upload image
		self::upload($src, $imagePath);

		$imagePaths['main'] = $imagePath;

		if (config('imageThumb.enable') || $createThumb) {

			$thumb = null;
			$imageFn = null;

			switch ($extension) {
				case "jpg":
				case "jpeg":
					$thumb = imagecreatefromjpeg($imagePath);
					$imageFn = 'imagejpeg';
					break;
				case "png":
					$thumb = imagecreatefrompng($imagePath);
					$imageFn = 'imagepng';
					break;
				case "gif":
					$thumb = imagecreatefromgif($imagePath);
					$imageFn = 'imagegif';
					break;
				default:
					$thumb = null;
			}

			$originalSize = [
				"width"  => imagesx($thumb),
				"height" => imagesy($thumb),
			];

			$width = config('imageThumb.width');
			$height = config('imageThumb.height');

			//calculate aspects
			$originalAspect = $originalSize["width"] / $originalSize["height"];
			$thumbAspect    = $width / $height;


			if ($originalAspect >= $thumbAspect) {
				// If image is wider than thumbnail (in aspect ratio sense)
				$newHeight = $height;
				$newWidth  = $originalSize["width"] / ($originalSize["height"] / $height);
			} else {
				// If the thumbnail is wider than the image
				$newWidth  = $width;
				$newHeight = $originalSize["height"] / ($originalSize["width"] / $height);
			}

			$preparedThumb = imagecreatetruecolor($width, $height);

			// Resize and crop
			imagecopyresampled($preparedThumb,
							$thumb,
							0 - ($newWidth - $width) / 2, // Center the picture horizontally
							0 - ($newHeight - $height) / 2, // Center the picture vertically
							0,
							0,
							$newWidth,
							$newHeight,
							$originalSize["width"],
							$originalSize["height"]);

			$imgThumbsDir = config('UPLOAD.THUMBS') . '/' . (! empty($destinationDir) ? $destinationDir . '/' : '');

			if (!is_dir($imgThumbsDir)) {
			    mkdir($imgThumbsDir, 0755, true);
			}
			$thumbPath = $imgThumbsDir . $filename;

			$imageFn($preparedThumb, $thumbPath);

			$imagePaths['thumb'] = $thumbPath;
		}

		return $imagePaths;
	}

	static public function list($path, $pattern = '*') {

		return glob($path . $pattern, GLOB_BRACE);
	}

	static public function deleteFile($path) {
		@unlink($path);
	}

	static public function moveFile($path, $newPath) {
		@rename($path, $newPath);
	}


	static private function upload($src, $destination) {

		$uploaded = move_uploaded_file($src, $destination);
		if (!$uploaded) {
			throw new Exception('File upload failed from [src: ' . $src . '] to [destination: ' . $destination . ']');
		}
	}
}