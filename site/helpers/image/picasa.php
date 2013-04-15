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

    	$app	 = &JFactory::getApplication();	   		
		$params	 = &$app->getParams();

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
			$thumbWinner = $this->getThumbWinner($width, $height);
			$posX = ceil( ($width - $thumbWinner) / 2);
			$posY = ceil( ($height - $thumbWinner/$this->imageRatio) / 2);
		} else {
			$width = round($height*$this->imageRatio);
			$thumbWinner = $this->getThumbWinner($width, $height);
			$posX = ceil( ($width - $thumbWinner) / 2);
			$posY = 0;
		}
		// css verschiebung berechnen
		
    	return '<img src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'" 
    				 style="background-repeat:no-repeat; 
    						background-image:url(\''.$this->getThumbUrl($width,$height,true,false).'\');
    						background-position: 50% 50%;" 
    						alt="" />';
    }
    
    public function getThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {
    	
    	$thumbWinner = $this->getThumbWinner($width, $height, true, $crop);

    	// css verschiebung berechnen
		$posX = ceil( ($width - $thumbWinner) / 2);
		$posY = ceil( ($height - $thumbWinner/$this->imageRatio) / 2);
		
		if ($crop) {
			$posY = ceil( ($height - $thumbWinner) / 2);
		} 
		
    	return '<img class="'.$cssClass.'" 
    				 src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'" 
    				 style="background-repeat:no-repeat; 
    						background-image:url(\''.$this->getThumbUrl($width,$height,true,$crop).'\');
    						background-position: 50% 50%;
							filter: progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height,true,$crop).'\', sizingMethod=\'scale\'); 
							-ms-filter: &qout;progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height,true,$crop).'\', sizingMethod=\'scale\')&quot;;
							" 
    				 alt="" />';
    }
    
    public function getLazyThumbImgTag($width=104,  $height=104, $cssClass="") {
    
    	$thumbWinner = $this->getThumbWinner($width, $height);
    	// css verschiebung berechnen
		$posX = ceil( ($width - $thumbWinner) / 2);
		$posY = ceil( ($height - $thumbWinner/$this->imageRatio) / 2);
		
		$imgTag = '<img class="lazyme '.$cssClass.'"
										data-width="'.$this->width.'"
										data-height="'.$this->height.'"	
								    	longdesc="'.$this->getThumbUrl($width,$height).'"
								    	src="'.JURI::base().'components/com_eventgallery/helpers/blank.php?width='.$width.'&amp;height='.$height.'"
								    	style="background-position: 50% 50%; background-repeat:no-repeat;"
										alt=""
					    			/>';
		
		return $imgTag;
    
    }
    
    public function getImageUrl($width, $height, $fullsize, $larger=false) {
    	if ($fullsize) {
    		return $this->image;
    	}else {
    		if ($this->imageRatio<1) {
    			return $this->getThumbUrl($height*$this->imageRatio, 1, $larger);
    		} else {
    			return $this->getThumbUrl($width,1, $larger);
    		}
    	}
    }
    
    public function getThumbUrl ($width, $height, $larger=true, $crop=false) {
    	if ($crop) {
    		$thumbWinner = $this->getThumbWinner($width, $height, $larger, true);
    		return $this->thumbsCrop[$thumbWinner];
    	} else {
    		$thumbWinner = $this->getThumbWinner($width, $height, $larger, false);
    		return $this->thumbs[$thumbWinner];
    	}
    	
    }
    
    /**
    * $crop means: use cropThumbs
    */

    private function getThumbWinner ($width=104,  $height=104, $larger=true, $crop=false) {
    	
    	if ($width==0) $width=104;
    	if ($height==0) $height=104;
    	
		$thumbRatio = $width / $height;
		
		
		if ($width/$this->imageRatio<=$height) {
			$width = ceil($height * $this->imageRatio);
		}
		
		$winner = 104;
		
		$diff = 9999;
		if (!$larger) {
			$diff = -9999;
		}
		
		$thumbs = Array();
		
		if ($crop) {
			$thumbs = $this->thumbsCrop;
		} else {
			$thumbs = $this->thumbs;
		}
		
		// passende Bildgröße berechnen.
		foreach($thumbs as $key=>$value) {
			$newDiff = $key-$width;
			
			if ($larger) {
				if ($newDiff>=0 && $diff>$newDiff) {
					$diff=$newDiff; 
					$winner = $key;
				}
			} else {
				if ($newDiff<=0 && $newDiff>$diff) {
					$diff=$newDiff; 
					$winner = $key;
				}	
			}
		}
		
		return $winner;
    }

}
	
?>