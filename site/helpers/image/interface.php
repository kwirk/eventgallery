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
	    
    public function getFullImgTag($width,  $height);
	    
    public function getThumbImgTag($width,  $height, $cssClass);
	    
    public function getLazyThumbImgTag($width,  $height, $cssClass, $crop);
	    
    public function getImageUrl($width, $height, $fullsize);	    
	    
    public function getThumbUrl ($width, $height, $larger, $crop);
	
}
	
	
?>