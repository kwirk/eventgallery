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

class EventgalleryLibraryManagerFolder extends  EventgalleryLibraryManagerManager
{

    protected $_folders;
    protected $_commentCount;


    /**
     * Returns a folder
     *
     * @param $foldername
     * @return EventgalleryLibraryFolder
     */
    public function getFolder($foldername) {

        if (is_object($foldername)) {
            $currentFolder = $foldername->folder;
        } else {
            $currentFolder = $foldername;
        }

        if (!isset($this->_folders[$currentFolder])) {

            if (strpos($currentFolder, '@')>0) {
                $this->_folders[$currentFolder] = new EventgalleryLibraryFolderPicasa($foldername);
            } else {
                $this->_folders[$currentFolder] = new EventgalleryLibraryFolderLocal($foldername);
            }

        }

        return $this->_folders[$currentFolder];
    }

    function getCommentCount($foldername)
    {
        if (!$this->_commentCount)
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true)
                ->select('folder, count(1) AS '.$db->quoteName('commentCount'))
                ->from($db->quoteName('#__eventgallery_comment'))
                ->where('published=1')
                ->group('folder');
            $db->setQuery($query);
            $comments = $db->loadObjectList();
            $this->_commentCount = array();
            foreach($comments as $comment)
            {
                $this->_commentCount[$comment->folder] = $comment->commentCount;
            }
        }

        if (isset($this->_commentCount[$foldername])) {
            return $this->_commentCount[$foldername];
        }

        return 0;
    }

    


}
