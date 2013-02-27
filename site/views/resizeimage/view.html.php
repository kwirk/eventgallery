<?php 
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');


class EventgalleryViewResizeimage extends JViewLegacy
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$file=JRequest::getString('file');
		$folder=JRequest::getString('folder');		

		$width=JRequest::getInt('width',-1);
		$height=JRequest::getInt('height',-1);
		
		
		if ($width>1441) $width = 1440;
		if ($height>1441) $height = 1440;
		
		$mode=JRequest::getString('mode','nocrop');	
		if (strcmp($mode,'full')==0) {
			$mode = 'nocrop';
			$width = 1440;
			$height = 1440;
		}	
		


		$file = STR_REPLACE("\.\.","",$file);
		$folder = STR_REPLACE("\.\.","",$folder);
		$width = STR_REPLACE("\.\.","",$width);
		$height = STR_REPLACE("\.\.","",$height);
		$mode = STR_REPLACE("\.\.","",$mode);
		
		$file = STR_REPLACE("/","",$file);
		$folder = STR_REPLACE("/","",$folder);
		$width = STR_REPLACE("/","",$width);
		$height = STR_REPLACE("/","",$height);
		$mode = STR_REPLACE("/","",$mode);
		
		$file = STR_REPLACE("\\","",$file);
		$folder = STR_REPLACE("\\","",$folder);
		$width = STR_REPLACE("\\","",$width);
		$height = STR_REPLACE("\\","",$height);
		$mode = STR_REPLACE("\\","",$mode);
		
		

		$basedir=JPATH_BASE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
		$sourcedir=$basedir.$folder;
		$cachebasedir=JPATH_CACHE.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR ;
		$cachedir=$cachebasedir.$folder;
		$cachedir_thumbs=$cachebasedir.$folder.DIRECTORY_SEPARATOR.'thumbs';
		
		


		if (!is_dir($cachebasedir))
		{
			mkdir($cachebasedir, 0777);
			
		}

		if (!is_dir($cachedir))
		{
			mkdir($cachedir, 0777);
		}

		if (!is_dir($cachedir_thumbs))
		{
			mkdir($cachedir_thumbs, 0777);

		}



		$image_file = $sourcedir.DIRECTORY_SEPARATOR.$file;
		$image_thumb_file = $cachedir_thumbs.DIRECTORY_SEPARATOR.$mode.$width.'_'.$height.$file;
		$last_modified = gmdate('D, d M Y H:i:s T', filemtime ($image_file));

	

		$debug = false;
		if ($debug || !file_exists($image_thumb_file))
		{

		 	
			$ext = substr($image_file, -3);

			if (strtolower($ext) == "gif") {
				if (!$im_original = imagecreatefromgif($image_file)) {
					echo "Error opening $image_file!"; exit;
				}
			} else if(strtolower($ext) == "jpg") {
				if (!$im_original = imagecreatefromjpeg($image_file)) {
					echo "Error opening $image_file!"; exit;
				}
			} else if(strtolower($ext) == "png") {
				if (!$im_original = imagecreatefrompng($image_file)) {
					echo "Error opening $image_file!"; exit;
				}
			} else {
				die;
			}
		
			$params	 = &$app->getParams();			

			$orig_width = imagesx($im_original);
            $orig_height = imagesy($im_original);
            $orig_ratio = imagesx($im_original)/imagesy($im_original);


			// create canvas/border image
  			if ($height<0 && $width<0) {
  				$height = 100;
  				$width = 100;
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
            	if ($height<0) {
            		$height=$width;
            	}
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
            
            $sharpenMatrix = array(
                                 array(-1,-1,-1),
                                 array(-1,16,-1),
                                 array(-1,-1,-1)
                                 );
            $divisor = 8;
            $offset = 0;
            
            if (function_exists('imageconvolution'))
            {
                imageconvolution($im_output, $sharpenMatrix, $divisor, $offset);
            
            }   

            imagejpeg($im_output,$image_thumb_file,80);      
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
?>