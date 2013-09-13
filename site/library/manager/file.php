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

class EventgalleryLibraryManagerFile extends  EventgalleryLibraryManagerManager
{

    protected $_folders;


    /**
     * Returns a file
     *
     * @param $foldername
     * @param $filename
     * @return EventgalleryLibraryFile
     */
    public function getFile($foldername, $filename = null) {

        if (is_object($foldername)) {
            $currentFolder = $foldername->folder;
            $filename = $foldername->file;

        } else {
            $currentFolder = $foldername;
        }

        if (!isset($this->_folders[$currentFolder][$filename])) {

            if (strpos($currentFolder, '@')>0) {
                $this->_folders[$currentFolder][$filename] = new EventgalleryLibraryFilePicasa($foldername, $filename);
            } else {
                $this->_folders[$currentFolder][$filename] = new EventgalleryLibraryFileLocal($foldername, $filename);
            }

        }

        return $this->_folders[$currentFolder][$filename];
    }



    


}
