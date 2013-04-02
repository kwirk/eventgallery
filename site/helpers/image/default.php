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

		// constructor
	    public function __construct($photo) {		    
	    	foreach((array)$photo as $key=>$value) {
	    		if (substr($key,1,1)!='*') {
	    			$this->$key = $value;
	    		}
	    	}
	    }

	    public function getTitle() {

	    	$app	 = &JFactory::getApplication();	   		
			$params	 = &$app->getParams();

			$showExif = $params->get('show_exif', true);

			$exif = "";
			if ($showExif && isset($this->exif)) {

				$exif = '<div class="img-exif">'.$this->exif->model.", ".$this->exif->focallength." mm, f/".$this->exif->fstop.", ISO ".$this->exif->iso."</div>";

				
			}

			$caption = "";
			if (isset($this->caption) && strlen($this->caption)>0) {
				$caption = '<div class="img-caption">'.$this->caption.'</div>';
			}
	    	return $caption.$exif;
	    }

	    public function hasTitle() {
	    	if (strlen($this->getTitle())>0) {
	    		return true;
	    	}

	    	return false;
	    }

	    public function getPlainTextTitle() {
	    	return strip_tags($this->caption);
	    }
	    
	
}

	
	
?>