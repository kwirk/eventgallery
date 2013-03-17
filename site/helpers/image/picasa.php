<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class EventgalleryHelpersImagePicasa implements EventgalleryHelpersImageInterface{
	
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
		
    	return '<img width="'.$width.'" height="'.$height.'" src="'.JURI::base().'components/com_eventgallery/media/images/blank.gif" style="background-image:url('.$this->getThumbUrl($width,$height,true,false).');background-position: '.$posX.'px '.$posY.'px;" alt="" />';
    }
    
    public function getThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {
    	
    	$thumbWinner = $this->getThumbWinner($width, $height, true, $crop);

    	// css verschiebung berechnen
		$posX = ceil( ($width - $thumbWinner) / 2);
		$posY = ceil( ($height - $thumbWinner/$this->imageRatio) / 2);
		
		if ($crop) {
			$posY = ceil( ($height - $thumbWinner) / 2);
		} 
		
    	return '<img class="'.$cssClass.'" width="'.$width.'" height="'.$height.'" src="'.JURI::base().'components/com_eventgallery/media/images/blank.gif" style="background-image:url('.$this->getThumbUrl($width,$height,true,$crop).');background-position: '.$posX.'px '.$posY.'px;" alt="" />';
    }
    
    public function getLazyThumbImgTag($width=104,  $height=104, $cssClass="") {
    
    	$thumbWinner = $this->getThumbWinner($width, $height);
    	// css verschiebung berechnen
		$posX = ceil( ($width - $thumbWinner) / 2);
		$posY = ceil( ($height - $thumbWinner/$this->imageRatio) / 2);
		
		$imgTag = '<img class="lazyme '.$cssClass.'"
										data-width="'.$this->width.'"
										data-height="'.$this->height.'"
								    	height="'.$height.'" 
								    	width="'.$width.'" 
								    	longdesc="'.$this->getThumbUrl($width,$height).'"
								    	src="'.JURI::base().'components/com_eventgallery/media/images/blank.gif"
								    	style="background-position:'.$posX.'px '.$posY.'px"
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