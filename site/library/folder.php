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


class EventgalleryLibraryFolder extends EventgalleryLibraryDatabaseObject
{

    /**
     * @var string
     */
    protected $_foldername = NULL;

    /**
     * @var TableFolder
     */
    protected $_folder = NULL;

    /**
     * @var EventgalleryLibraryImagetypeset
     */
    protected $_imagetypeset = NULL;

    /**
     * $creates the lineitem object. $dblineitem is the database object of this line item
     */
    function __construct($foldername)
    {
        $this->_foldername = $foldername;
        $this->_loadFolder();
        parent::__construct();
    }

    /**
     * loads a folder from the databas
     */
    protected function _loadFolder()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__eventgallery_folder');
        $query->where('folder=' . $db->Quote($this->_foldername));

        $db->setQuery($query);
        $folderObject = $db->loadObject();
        $this->_folder = $folderObject;

        if ($this->_folder->imagetypesetid == null) {
            /**
             * @var EventgalleryLibraryManagerImagetypeset $imagetypesetMgr
             */
            $imagetypesetMgr = EventgalleryLibraryManagerImagetypeset::getInstance();
            $this->_imagetypeset = $imagetypesetMgr->getDefaultImageTypeSet();
        } else {
            $this->_imagetypeset = new EventgalleryLibraryImagetypeset($this->_folder->imagetypesetid);
        }

    }

    /**
     * @return string
     */
    public function getFolderName()
    {
        return $this->_folder->folder;
    }

    /**
     * @return EventgalleryLibraryImagetypeset
     */
    public function getImageTypeSet()
    {
        return $this->_imagetypeset;
    }

    /**
     * @return bool
     */
    public function isCartable()
    {
        return $this->_folder->cartable == 1;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->_folder->published == 1;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_folder->password;
    }

    /**
     * @return bool
     */
    public function isAccessible()
    {

        if (strlen($this->getPassword()) > 0) {
            $session = JFactory::getSession();
            $unlockedFoldersJson = $session->get("eventgallery_unlockedFolders", "");

            $unlockedFolders = array();
            if (strlen($unlockedFoldersJson) > 0) {
                $unlockedFolders = json_decode($unlockedFoldersJson, true);
            }

            if (!in_array($this->_foldername, $unlockedFolders)) {
                return false;
            }
        }

        return true;
    }

}
