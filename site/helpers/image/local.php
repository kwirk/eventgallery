<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;	


class EventgalleryHelpersImageLocal extends EventgalleryHelpersImageDefault{

		protected $_image_script_path = 'components/com_eventgallery/helpers/image.php';
		protected $_blank_script_path = 'components/com_eventgallery/helpers/blank.php';

		// constructor
    /** @noinspection PhpUndefinedClassInspection */
    public function __construct($photo) {
	    	foreach((array)$photo as $key=>$value) {
	    		if (substr($key,1,1)!='*') {
	    			$this->$key = $value;
	    		}
	    	}


            $app	 = JFactory::getApplication();
            $params = null;
            if ($app instanceof JSite) {
                /**
                 * @var JSite $app
                 */
				$params	 = $app->getParams();

			}else {
                /**
                 * @var JAdministrator $app
                 */
                $params = JComponentHelper::getParams('com_eventgallery');
            }

            if ($params->get('use_legacy_image_rendering','0')=='1') {
                $this->_image_script_path = "index.php";
                $this->_blank_script_path = "components/com_eventgallery/media/images/blank.gif";
            }


	    }
	    
	    public function getFullImgTag($width=104,  $height=104) {
	    	
	    	return '<img src="'.JURI::root().$this->_blank_script_path.'?width='.$width.'&amp;height='.$height.'" 
	    	             style="background-repeat:no-repeat;
	    	    				background-position: 50% 50%; 
	    	    				background-image:url(\''.$this->getThumbUrl($width,$height,false,true).'\');
	    	    				" 
	    				alt="" />';
	    	
	    }
	    
	    public function getThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {
	    	return '<img src="'.JURI::root().$this->_blank_script_path.'?width='.$width.'&amp;height='.$height.'" 
	    				style="	background-repeat:no-repeat; 
	    						background-position: 50% 50%; 
	    						background-image:url(\''.$this->getThumbUrl($width,$height, true, $height==$width).'\');
								filter: progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height, true, $height==$width).'\', sizingMethod=\'scale\'); 
								-ms-filter: &quot;progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height, true, $height==$width).'\', sizingMethod=\'scale\')&quot;;

								
	    						" 
	    				alt="" 
	    				class="'.$cssClass.'"/>';
	    }
	    
	    public function getLazyThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {
    		$imgTag = '<img class="lazyme '.$cssClass.'"
    									data-width="'.$this->width.'"
										data-height="'.$this->height.'"
								    	longdesc="'.$this->getThumbUrl($width,$height, true, $crop).'"
								    	src="'.JURI::root().$this->_blank_script_path.'?width='.$width.'&amp;height='.$height.'"
								    	style="background-position: 50% 50%; background-repeat: no-repeat;"
								    	alt=""
					    			/>';
			return $imgTag;
	    }
	    
	    public function getImageUrl($width=104,  $height=104, $fullsize, $larger=false) {
	    	if ($fullsize) {		    		
	    		return JURI::root().$this->_image_script_path."?option=com_eventgallery&mode=full&view=resizeimage&folder=".$this->folder."&file=".urlencode($this->file);
	    	} else {   		

	    		if ($height>$width) {
	    			$width = $height;
	    		} 

		    	return JURI::root().$this->_image_script_path."?option=com_eventgallery&width=".$width."&view=resizeimage&folder=".$this->folder."&file=".urlencode($this->file);
	    	}
	    }
	    
	    
	    
	    public function getThumbUrl ($width=104, $height=104, $larger=true, $crop=false) {	    
	    	
	    	if ($crop) {
	    		$mode = 'crop';
	    	} else {
	    		$mode = 'uncrop';
	    	}	    	    	

	    	if ($height>$width) {
	    		$width = $height;
	    	}
	    				    	
	    	return JURI::root().$this->_image_script_path."?option=com_eventgallery&mode=".$mode."&width=".$width."&view=resizeimage&folder=".$this->folder."&file=".urlencode($this->file);
	    }
	
}
