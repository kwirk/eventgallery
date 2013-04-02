<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;	


abstract class EventgalleryHelpersImageDefault implements EventgalleryHelpersImageInterface{

	    /**
	    * returns the title of an image. Same as lightbox but without :: char.
	    */
	    public function getTitle() {
	    	return str_replace("::", "", $this->getLightBoxTitle());
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

			$showExif = $params->get('show_exif', true);

			$exif = "";
			if ($showExif && isset($this->exif)) {
				$exif = '<span class="img-exif">'.$this->exif->model.", ".$this->exif->focallength." mm, f/".$this->exif->fstop.", ISO ".$this->exif->iso."</span>";				
			}

			$caption = "";
			if (isset($this->caption) && strlen($this->caption)>0) {


				$c = preg_split("/::/", $this->caption, 2);

				$caption = '<span class="img-caption img-caption-part1">'.$c[0].'</span>';
				if (isset($c[1])) {
					$caption .= ':: <span class="img-caption img-caption-part2">'.$c[1].'</span>';
				}
			}

			if (!strpos($caption, "::") && strlen($exif)>0) {
				return $caption."::".$exif;
			}

	    	return $caption.$exif;
	    }

	    /**
	    * checks if the image has a title to show.
	    */
	    public function hasTitle() {
	    	if (strlen($this->getLightBoxTitle())>0) {
	    		return true;
	    	}

	    	return false;
	    }

	    /**
	    * returns the title of an image. Returns the part before the :: only and strips out all tag elements
	    */
	    public function getPlainTextTitle() {
	    	$c = preg_split("/::/", strip_tags($this->caption));
	    	
	    	if (isset($this->caption)) {
	    		return $c[0];
	    	}	

	    	return "";
	    }
	    
	
}

	
	
?>