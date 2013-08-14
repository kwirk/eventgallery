<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');
require_once JPATH_BASE.'/components/com_eventgallery/helpers/vendors/class.jpeg_icc.php';	
/* Load the required PEL files for handling JPEG images. */
require_once JPATH_BASE.'/components/com_eventgallery/helpers/vendors/pel/src/PelJpeg.php';


class EventgalleryViewResizeimage extends JViewLegacy
{

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$params	 = $app->getParams();			

		$file=JRequest::getString('file');
		$folder=JRequest::getString('folder');		

		$width=JRequest::getInt('width',-1);
		$height=JRequest::getInt('height',-1);
				
		$mode=JRequest::getString('mode','nocrop');	

		if (strcmp($mode,'full')==0) {
			$mode = 'nocrop';
			$width = 5000;
			$height = 5000;
		}	

		if ($height>$width) {
			$width=$height;
		}
		
		$sizeSet = new EventgalleryHelpersSizeset();
		$saveAsSize = $sizeSet->getMatchingSize($width);
	

		$file = STR_REPLACE("\.\.","",$file);
		$folder = STR_REPLACE("\.\.","",$folder);
		$width = STR_REPLACE("\.\.","",$width);
		$mode = STR_REPLACE("\.\.","",$mode);
		
		$file = STR_REPLACE("/","",$file);
		$folder = STR_REPLACE("/","",$folder);
		$width = STR_REPLACE("/","",$width);
		$mode = STR_REPLACE("/","",$mode);
		
		$file = STR_REPLACE("\\","",$file);
		$folder = STR_REPLACE("\\","",$folder);
		$width = STR_REPLACE("\\","",$width);
		$mode = STR_REPLACE("\\","",$mode);
		
		

		$basedir=JPATH_BASE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
		$sourcedir=$basedir.$folder;
		$cachebasedir=JPATH_CACHE.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR ;
		$cachedir=$cachebasedir.$folder;
		$cachedir_thumbs=$cachebasedir.$folder.DIRECTORY_SEPARATOR.'thumbs';
		
		if (!is_dir(JPATH_CACHE))
		{
			//mkdir($cachebasedir, 0777);
			mkdir(JPATH_CACHE);
			
		}		

		if (!is_dir($cachebasedir))
		{
			//mkdir($cachebasedir, 0777);
			mkdir($cachebasedir);
			
		}

		if (!is_dir($cachedir))
		{
			//mkdir($cachedir, 0777);
			mkdir($cachedir);
		}

		if (!is_dir($cachedir_thumbs))
		{
			//mkdir($cachedir_thumbs, 0777);
			mkdir($cachedir_thumbs);

		}

		$image_file = $sourcedir.DIRECTORY_SEPARATOR.$file;
		$image_thumb_file = $cachedir_thumbs.DIRECTORY_SEPARATOR.$mode.$saveAsSize.$file;
		//$last_modified = gmdate('D, d M Y H:i:s T', filemtime ($image_file));
		$last_modified = gmdate('D, d M Y H:i:s T', mktime(0, 0, 0, 1, 1, 2100));
		#echo "<br>".$image_thumb_file."<br>";

		$debug = false;
		$input_jpeg = null;

		if ($debug || !file_exists($image_thumb_file))
		{
		 	
			$ext = pathinfo($image_file, PATHINFO_EXTENSION);;

			if (strtolower($ext) == "gif") {
				if (!$im_original = imagecreatefromgif($image_file)) {
					echo "Error opening $image_file!"; exit;
				}
			} else if(strtolower($ext) == "jpg" || strtolower($ext) == "jpeg") {
				$input_jpeg = null;
				// try to use PEL first. If things fail, use the php internal method to get the JPEG
				try {
					$input_jpeg = new PelJpeg($image_file);
					/* The input image is already loaded, so we can reuse the bytes stored
				     * in $input_jpeg when creating the Image resource. */
					if (!$im_original = ImageCreateFromString($input_jpeg->getBytes())) {
						echo "Error opening $image_file!"; exit;
					}
				} catch (Exception $e){
					if (!$im_original = imagecreatefromjpeg($image_file)) {
						echo "Error opening $image_file!"; exit;
					}
				}
				
			} else if(strtolower($ext) == "png") {
				if (!$im_original = imagecreatefrompng($image_file)) {
					echo "Error opening $image_file!"; exit;
				}
			} else {
				die("$ext not supported");
			}
		

			$orig_width = imagesx($im_original);
            $orig_height = imagesy($im_original);
            $orig_ratio = imagesx($im_original)/imagesy($im_original);

            $sizeCalc = new EventgalleryHelpersSizecalculator($orig_width, $orig_height, $width, strcmp('crop',$mode)==0);			
			$height = $sizeCalc->getHeight();
			$width = $sizeCalc->getWidth();
			//print_r($sizeCalc);
			// create canvas/border image

  			//adjust height to not enlarge images
  			if ($width>$orig_width) {
  				$width = $orig_width;
  			}

  			if ($height>$orig_height) {
  				$height = $orig_height;
  			}
        	
        	if (strcmp('crop',$mode)!=0) {         
                $canvasWidth=$width;
                $canvasHeight=ceil($width/$orig_ratio);
                
                if ($canvasHeight>$height) {
                    $canvasHeight=$height;
                    $canvasWidth=ceil($height*$orig_ratio);
                    $width = $canvasWidth;
                
                }
                $width = $canvasWidth;
                $height = $canvasHeight;                
            } else {
            	$height=$width;
            }
            
            $im_output= imagecreatetruecolor($width,$height);
            
            $resize_faktor = $orig_height / $height;
            $new_height = $height;
            $new_width = $orig_width / $resize_faktor;   
            
            if ($new_width<$width)
            {
                $resize_faktor = $orig_width / $width;
                $new_width = $width;
                $new_height = $orig_height / $resize_faktor;
            }
        
            imagecopyresampled($im_output, $im_original,
                                 ($width/2)-($new_width/2),
                                 ($height/2)-($new_height/2),
                                 0,0,
                                 $new_width,$new_height,$orig_width,$orig_height);
            
            $use_sharpening = $params->get('use_sharpening',1);

            if ($use_sharpening==1) {
	            // configure the sharpening
	            $stringSharpenMatrix = $params->get('image_sharpenMatrix','[[-1,-1,-1],[-1,16,-1],[-1,-1,-1]]');

	        	$sharpenMatrix = json_decode($stringSharpenMatrix);
	        	if (null == $sharpenMatrix || count($sharpenMatrix)!=3) {
		            $sharpenMatrix = array(
		                                 array(-1,-1,-1),
		                                 array(-1,16,-1),
		                                 array(-1,-1,-1)
		                                 );
	        	}

	           $divisor = array_sum(array_map('array_sum', $sharpenMatrix));
	            $offset = 0;
	            
	            if (function_exists('imageconvolution'))
	            {
	                imageconvolution($im_output, $sharpenMatrix, $divisor, $offset);
	            
	            }   
        	}

			$image_quality = $params->get('image_quality',85);
			if ($input_jpeg != null) {
				Pel::setJPEGQuality($image_quality);
				/* We want the raw JPEG data from $scaled. Luckily, one can create a
				 * PelJpeg object from an image resource directly: */
				$output_jpeg = new PelJpeg($im_output);

				/* Retrieve the original Exif data in $jpeg (if any). */
				$exif = $input_jpeg->getExif();

				/* If no Exif data was present, then $exif is null. */
				if ($exif != null)
				  $output_jpeg->setExif($exif);

				/* We can now save the scaled image. */
				$writeSuccess = true;
				$output_jpeg->saveFile($image_thumb_file);
			} else {

	            $writeSuccess = imagejpeg($im_output,$image_thumb_file, $image_quality);     
	            if (!$writeSuccess) {
	            	die("Unable to write to file $image_thumb_file");
	            }
	        }
            
            
            $time = time() + 315360000;
            touch($image_thumb_file, $time);

            // save exif
            
            // add the ICC profile
            try {
	            $o = new JPEG_ICC();
				$o->LoadFromJPEG($image_file);
				$o->SaveToJPEG($image_thumb_file); 
			} catch (Exception $e) {

			}

		}
		
		

		if (!$debug)
		{
			header("Last-Modified: $last_modified");
			header("Content-Type: image/jpeg");
			
		}

		echo readfile($image_thumb_file);		
		$app->close();
	}
}
