<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;	


interface  EventgalleryHelpersImageInterface {
	    
    public function getFullImgTag($width=104,  $height=104);
	    
    public function getThumbImgTag($width=104,  $height=104, $cssClass="");
	    
    public function getLazyThumbImgTag($width=104,  $height=104, $cssClass="");
	    
    public function getImageUrl($width, $height, $fullsize);	    
	    
    public function getThumbUrl ($width, $height, $larger=true, $crop=false);
	
}
	
	
?>