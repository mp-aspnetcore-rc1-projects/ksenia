<?php

namespace MParaiso;
/**
* optimize image files,requires gd
*/
class Optimizer
{
	/**
	 * optimize an image file to jpgeg	
	 * @param  number $quality number between 0 and 100,default to 75
	 * @param  file $input   input file
	 * @param  file $output  output file
	 * @return return image handle    
	 */
	public function optimize($quality=75,$input,$output){
		$info=getimagesize($input);
		if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($input);
		elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($input);
		elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($input);
	# save file
		imagejpeg($image,$output,$quality);
		return $image;
	}
}
