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


class EventgalleryLibraryFolderLocal extends EventgalleryLibraryFolder
{

    /**
     * $creates the folder object.
     */
    function __construct($foldername)
    {
        parent::__construct($foldername);
    }

    /**
     * @param int $limitstart
     * @param int $limit
     * @param int $imagesForEvents if true load the main images at the first position
     * @return array
     */
    public function getFiles($limitstart = 0, $limit = 0, $imagesForEvents = 0) {

        // database handling
        // database handling
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select('file.*, COUNT(comment.id) AS '.$db->quoteName('commentCount'))
            ->from($db->quoteName('#__eventgallery_file') . ' AS file')
            ->join('INNER', $db->quoteName('#__eventgallery_folder') . ' AS folder ON folder.folder=file.folder and folder.published=1')
            ->join('LEFT', $db->quoteName('#__eventgallery_comment') . ' AS comment ON file.folder=comment.folder and file.file=comment.file')
            ->where('file.folder=' . $db->Quote($this->_foldername))
            ->where('file.published=1')
            ->group('file.id');

        if ($imagesForEvents == 0) {
            // find files which are allowed to show in a list
            $query->where('file.ismainimageonly=0')
                ->order('ordering DESC, file.file');
        } else {
            // find files and sort them with the main images first
            $query->order('file.ismainimage DESC, ordering DESC, file.file');
        }




        if ($limit != 0) {
            $db->setQuery($query, $limitstart, $limit);
        } else {
            $db->setQuery($query);
        }

        $entries = $db->loadObjectList();

        $result = Array();
        /**
         * @var EventgalleryLibraryManagerFile $fileMgr
         */
        $fileMgr = EventgalleryLibraryManagerFile::getInstance();

        foreach ($entries as $entry) {
            $result[] =  $fileMgr->getFile($entry);
        }


        return $result;

    }

}
