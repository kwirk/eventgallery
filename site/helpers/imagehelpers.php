<?php

/**
 * Trys to determine the border and available size of an Image
 * 
 * @param $im the image-object
 * @param $color intvalue of the color (e.g. 0 for black)
 * @return Array of the sizes
 */
function calcImagesize($im, $color)
{
	$backgroundcolor = 0;
	
	$width= imagesx($im);
	$height= imagesy($im);

	$middle_x = ceil($width/2);
	$middle_y = ceil($height/2);

	$count = $middle_x;
	while($count>=0 && imagecolorat($im, $count, $middle_y)==$backgroundcolor )  {    $count--;  }
	$border_left = $count+1;

	$count = $middle_x;
	while($count<$width && imagecolorat($im, $count, $middle_y)==$backgroundcolor)  {    $count++;  }
	$border_right = $width-$count;

	$count = $middle_y;
	while($count>=0 && imagecolorat($im, $middle_x, $count)==$backgroundcolor)  {    $count--;  }
	$border_top = $count+1;

	$count = $middle_y;
	while($count<$height && imagecolorat($im, $middle_x, $count)==$backgroundcolor)  {    $count++;  }
	$border_bottom = $height-$count;

	$image_height = $height-$border_top-$border_bottom;
	$image_width = $width-$border_left-$border_right;

	$scale = array(
         'height'=>$height,
         'width'=>$width,
         'image_height'=>$image_height,
         'image_width'=>$image_width,
         'border-top'=>$border_top,
         'border-bottom'=>$border_bottom,
         'border-left'=>$border_left,
         'border-right'=>$border_right
	);

	return $scale;
}

/**
 * Determine EXIF-Informations from a given image-File
 * 
 * @param $image_file the imagefile including the path
 * @return Array with EXIF-Informations
 */
function getExif($image_file)
{
	$Toolkit_Dir = JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'exif'.DIRECTORY_SEPARATOR ;     // Ensure dir name includes trailing slash
	//Hide any unknown EXIF tags
	$GLOBALS['HIDE_UNKNOWN_TAGS'] = TRUE;
	include_once $Toolkit_Dir . 'Toolkit_Version.php';          // Change: added as of version 1.11
	include_once $Toolkit_Dir . 'JPEG.php';                     // Change: Allow this example file to be easily relocatable - as of version 1.11
	include_once $Toolkit_Dir . 'JFIF.php';
	include_once $Toolkit_Dir . 'PictureInfo.php';
	include_once $Toolkit_Dir . 'XMP.php';
	include_once $Toolkit_Dir . 'Photoshop_IRB.php';
	include_once $Toolkit_Dir . 'EXIF.php';
	
	$exif = @get_EXIF_JPEG($image_file);
	
	return $exif;
        
}
?>