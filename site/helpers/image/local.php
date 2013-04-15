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

		// constructor
	    public function __construct($photo) {		    
	    	foreach((array)$photo as $key=>$value) {
	    		if (substr($key,1,1)!='*') {
	    			$this->$key = $value;
	    		}
	    	}
	    }
	    
	    public function getFullImgTag($width=104,  $height=104) {
	    	
	    	return '<img src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'" 
	    	             style="background-repeat:no-repeat;
	    	    				background-position: 50% 50%; 
	    	    				background-image:url(\''.$this->getThumbUrl($width,$height,false,true).'\');
	    	    				" 
	    				alt="" />';
	    	
	    }
	    
	    public function getThumbImgTag($width=104,  $height=104, $cssClass="") {
	    	return '<img src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'" 
	    				style="	background-repeat:no-repeat; 
	    						background-position: 50% 50%; 
	    						background-image:url(\''.$this->getThumbUrl($width,$height, true, $height==$width).'\');
								filter: progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height, true, $height==$width).'\', sizingMethod=\'scale\'); 
								-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height, true, $height==$width).'\', sizingMethod=\'scale\')";

								
	    						" 
	    				alt="" 
	    				class="'.$cssClass.'"/>';
	    }
	    
	    public function getLazyThumbImgTag($width=104,  $height=104, $cssClass="") {
    		$imgTag = '<img class="lazyme '.$cssClass.'"
    									data-width="'.$this->width.'"
										data-height="'.$this->height.'"
								    	longdesc="'.$this->getThumbUrl($width,$height).'"
								    	src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'"
								    	style="background-position: 50% 50%; background-repeat: no-repeat;"
								    	alt=""
					    			/>';
			return $imgTag;
	    }
	    
	    public function getImageUrl($width, $height, $fullsize, $larger=false) {
	    	if ($fullsize) {		    		
	    		return JURI::base()."components/com_eventgallery/helpers/image.php?option=com_eventgallery&mode=full&view=resizeimage&folder=".$this->folder."&file=".urlencode($this->file);
	    	} else {   		

	    		if ($height>$width) {
	    			$width = $height;
	    		} 

		    	return JURI::base()."components/com_eventgallery/helpers/image.php?option=com_eventgallery&width=".$width."&view=resizeimage&folder=".$this->folder."&file=".urlencode($this->file);
	    	}
	    }
	    
	    
	    
	    public function getThumbUrl ($width, $height, $larger=true, $crop=false) {	    
	    	
	    	if ($crop) {
	    		$mode = 'crop';
	    	} else {
	    		$mode = 'uncrop';
	    	}	    	    	

	    	if ($height>$width) {
	    		$width = $height;
	    	}
	    				    	
	    	return JURI::base()."components/com_eventgallery/helpers/image.php?option=com_eventgallery&mode=".$mode."&width=".$width."&view=resizeimage&folder=".$this->folder."&file=".urlencode($this->file);
	    }
	
}

	
	
?>