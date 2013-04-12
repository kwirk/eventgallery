<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventgalleryHelpersSizeset {

	var $availableSizes = Array(32,48,64,72,94,104,110,128,144,150,160,200,220,288,320,400,512,576,640,720,800,912,1024,1152,1280,1440);
	public function getMatchingSize($size) {
		$finalSize = $this->availableSizes[count($this->availableSizes)-1];
		foreach($this->availableSizes as $option) {
			if ($option>=$size) {
				return $option;
			}
		}
		return $finalSize;
	}
}
