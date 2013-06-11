<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class EventgalleryLibraryFile extends EventgalleryLibraryFolder implements EventgalleryHelpersImageInterface
{

	protected $_filename = null;
	protected $_file = null;

	/**
	* $creates the lineitem object. $dblineitem is the database object of this line item
	*/
	function __construct($foldername, $filename)
	{	
		parent::__construct($foldername);	 			 		
		$this->_filename = $filename;
		$this->_loadFile();
	    
	}	

	protected function _loadFile() {
		$fileObject = null;

		if (strpos($this->_foldername,'@')>-1) {
			$values = explode("@",$this->_foldername,2);
			$folderObject = $this->_folder;
			$picasakey = $folderObject->picasakey;
			$album = EventgalleryHelpersImageHelper::picasaweb_ListAlbum($values[0], $values[1], $picasakey);

			foreach ($album->photos as $photo) {

				if (strcmp($photo->file, $this->_filename)==0) {
					$fileObject = new EventgalleryHelpersImagePicasa($photo);
					break;
				}
				
			}
            
		} else {
    	
    	    $db = JFactory::getDBO();
    	    $query = $db->getQuery(true);
    	    $query->select('*');
    	    $query->from('#__eventgallery_file');
    	    $query->where('folder='.$db->Quote($this->_foldername));
    	    $query->where('file='.$db->Quote($this->_filename));            
            $db->setQuery($query);            
            $result = $db->loadObject();
            $fileObject = new EventgalleryHelpersImageLocal($result);        	            
		}

		
		$this->_file = $fileObject;

	}

	public function getCartThumb($lineitemid) {

		return $this->_file->getCartThumb($lineitemid);
	}

	public function getFileName() {
		return $this->_file->file;
	}

	public function isPublished() {
		return $this->_folder->published==1 && $this->_file->published==1;
	}


    public function getFullImgTag($width = 104, $height = 104)
    {
        return $this->_file->getFullImgTag($width, $height);
    }

    public function getThumbImgTag($width = 104, $height = 104, $cssClass = "", $crop = false)
    {
        return $this->_file->getThumbImgTag($width, $height, $cssClass, $crop);
    }

    public function getLazyThumbImgTag($width = 104, $height = 104, $cssClass = "", $crop = false)
    {
        return $this->_file->getLazyThumbImgTag($width, $height, $cssClass, $crop);
    }

    public function getImageUrl($width = 104, $height = 104, $fullsize, $larger = false)
    {
        return $this->_file->getImageUrl($width, $height, $fullsize, $larger);
    }

    public function getThumbUrl($width = 104, $height = 104, $larger = true, $crop = false)
    {
        return $this->_file->getThumbUrl($width, $height, $larger, $larger);
    }
}
