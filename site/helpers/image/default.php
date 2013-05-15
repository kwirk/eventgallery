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

	    	$app	 = JFactory::getApplication();	   		
			$params	 = $app->getParams();

			$showExif = $params->get('show_exif','1')=='1';

			$caption = "";

			if (isset($this->title) && strlen($this->title)>0) {	
				$caption .= '<span class="img-caption img-caption-part1">'.$this->title.'</span>';
			}

			if (isset($this->caption) && strlen($this->caption)>0) {

				if (strlen($caption)>0) {
					$caption .= "::";
				}
				$caption .= '<span class="img-caption img-caption-part2">'.$this->caption.'</span>';
				
			}
			

	    	return $caption;
	    }

	    /**
	    * checks if the image has a title to show.
	    */
	    public function hasTitle() {
	    	if (strlen($this->getTitle())>0) {
	    		return true;
	    	}

	    	return false;
	    }

	    /**
	    * returns the title of an image. Returns the part before the :: only and strips out all tag elements
	    */
	    public function getPlainTextTitle() {

			if (isset($this->title)) {
	    		return strip_tags($this->title);
	    	}

	    	if (isset($this->caption)) {
	    		return strip_tags($this->caption);
	    	}	

	    	return "";
	    }
	    
	
}

	
	
?>