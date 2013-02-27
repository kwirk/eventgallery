<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
 defined('_JEXEC') or die;

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

?>