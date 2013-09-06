<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class EventgalleryHelpersImagePicasa extends EventgalleryHelpersImageDefault{
	
	// constructor
    public function __construct($photo) {
    	foreach((array)$photo as $key=>$value) {
    		$this->$key = $value;
    	}
		
		if (isset($this->height)) {
			$this->imageRatio = $this->width/$this->height;
		} else {
			$this->imageRatio = 1;
		}
    	
    }
    

	    /**
    *  returns a title with the following format:
    * 
    *   <span class="img-caption img-caption-part1">Foo</span>[::<span class="img-caption img-caption-part1">Bar</span>][::<span class="img-exif">EXIF</span>]
    * 
    *  :: is the separator for the lightbox to split in title and caption.
    */

    public function getLightBoxTitle() {

    	$app	 = JFactory::getApplication();	   		
		$params	 = $app->getParams();

		$showExif = $params->get('show_exif','1')=='1';

		$caption = "";

		if (isset($this->caption) && strlen($this->caption)>0) {
			$caption .= '<span class="img-caption img-caption-part1">'.$this->caption.'</span>';			
		}

		if ($showExif && isset($this->exif) && strlen($this->exif->model)>0 && strlen($this->exif->focallength)>0 && strlen($this->exif->fstop)>0) {
			$exif = '<span class="img-exif">'.$this->exif->model.", ".$this->exif->focallength." mm, f/".$this->exif->fstop.", ISO ".$this->exif->iso."</span>";				
			if (!strpos($caption, "::")) {
				$caption .= "::";
			}
			$caption .= $exif;
		}			

    	return $caption;
    }

    
    public function getFullImgTag($width=104,  $height=104) {
    	
    
		
		if ($this->imageRatio>=1) {
			$height = round($width/$this->imageRatio);			
		} else {
			$width = round($height*$this->imageRatio);
		}
		// css verschiebung berechnen
		
    	return '<img src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'" 
    				 style="width: '.$width.'px;
	    	             	height: '.$height.'px;
    				 		background-repeat:no-repeat; 
    						background-image:url(\''.$this->getThumbUrl($width,$height,true,false).'\');
    						background-position: 50% 50%;" 
    						alt="" />';
    }
    
    public function getThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {

    	return '<img class="'.$cssClass.'" 
    				 src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'" 
    				 style="width: '.$width.'px;
	    	             	height: '.$height.'px;
    				 		background-repeat:no-repeat; 
    						background-image:url(\''.$this->getThumbUrl($width,$height,true,$crop).'\');
    						background-position: 50% 50%;
							filter: progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height,true,$crop).'\', sizingMethod=\'scale\'); 
							-ms-filter: &qout;progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height,true,$crop).'\', sizingMethod=\'scale\')&quot;;
							" 
    				 alt="" />';
    }
    
    public function getLazyThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {
		
		$imgTag = '<img class="lazyme '.$cssClass.'"
										width: '.$width.'px;
	    	             				height: '.$height.'px;
										data-width="'.$this->width.'"
										data-height="'.$this->height.'"	
								    	longdesc="'.$this->getThumbUrl($width,$height, true, $crop).'"
								    	src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'"
								    	style="background-position: 50% 50%; background-repeat:no-repeat;"
										alt=""
					    			/>';
		
		return $imgTag;
    
    }
    
    public function getImageUrl($width=104,  $height=104, $fullsize, $larger=false) {
    	if ($fullsize) {
    		return $this->image;
    	}else {
    		if ($this->imageRatio<1) {
    			return $this->getThumbUrl($height*$this->imageRatio, $height, $larger);
    		} else {
    			return $this->getThumbUrl($width,$width/$this->imageRatio, $larger);
    		}
    	}
    }
    
    public function getThumbUrl ($width=104, $height=104, $larger=true, $crop=false) {
    	
    	if ($width==0) $width=104;
    	if ($height==0) $height=104;
    	$googlewidth = 104;

    	if ($this->width>$this->height) {
			// querformat
			$googlewidth = $width;
			$resultingHeight = $googlewidth/$this->imageRatio;
			if ($resultingHeight<$height) {
				$googlewidth=round($height*$this->imageRatio);
		    }
    	} else {
    	  	//hochformat
    		$googlewidth = $height;
    		$resultingWidth = $googlewidth*$this->imageRatio;
			if ($resultingWidth<$width) {
				$googlewidth=round($height/$this->imageRatio);
		    }
    	}
		

		$sizeSet = new EventgalleryHelpersSizeset();
		$saveAsSize = $sizeSet->getMatchingSize($googlewidth);
		
		//modify google image url
		$values = array_values($this->thumbs);
		$winner = str_replace('/s104/', "/s$saveAsSize/", $values[0]);

		return $winner;
    }

}