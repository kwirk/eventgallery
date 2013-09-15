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

    public static $SYNC_STATUS_NOSYNC = 0;
    public static $SYNC_STATUS_SYNC = 1;
    public static $SYNC_STATUS_DELTED = 2;


    protected $_folders;
    protected $_commentCount;
    protected $_maindir;

    public function __construct() {
        $this->_maindir = JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
    }

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

    /**
     * scans the main dir and adds new folders to the database
     * Does not add Files!
     */
    public function addNewFolders() {

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $folders = Array();

        if (file_exists($this->_maindir)) {
            $verzeichnis = dir($this->_maindir);
        } else {
            return;
        }

        # Hole die verfügbaren Verzeichnisse
        while ($elm = $verzeichnis->read())
        { //sucht alle Verzeichnisse mit Bilder
            if (is_dir($this->_maindir.$elm) && !preg_match("/\./",$elm) && !preg_match("/.cache/",$elm))
            {
                if (is_dir($this->_maindir.$elm.DIRECTORY_SEPARATOR ))
                {
                    array_push($folders, $elm);
                }
            }
        }

        # Füge Verzeichnisse in die DB ein
        foreach($folders as $folder)
        {
            #Versuchen wir, ein paar Infos zu erraten

            $date = "";
            $temp = array();
            $created = date('Y-m-d H:i:s',filemtime($this->_maindir.$folder));

            if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$folder, $temp))
            {
                $date = $temp[0];
                $description = str_replace($temp[0],'',$folder);
            }
            else {
                $description = $folder;
            }

            $description = trim(str_replace("_", " ", $description));

            $query = "insert IGNORE into #__eventgallery_folder
			            set folder=".$db->Quote($folder).",
			                 published=0,
			                 date=".$db->Quote($date).",
			                 description=".$db->Quote($description).",
			                 userid=".$db->Quote($user->id).",
			                 created=".$db->quote($created).",
			                 modified=NOW()
			         ;";
            $db->setQuery($query);
            $db->query();

        }

    }

    /**
     * Syncs a folder. Includes deletion and adding/removing files
     *
     * return values:
     *
     * synced
     * notsynced
     * deleted
     *
     * @param $folder
     * @return string
     */
    public function syncFolder($folder) {

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        if (strpos($folder,'@')>0) {
            return self::$SYNC_STATUS_NOSYNC;
        }

        $folderpath = $this->_maindir.$folder;
        if (!file_exists($folderpath)) {
            $this->deleteFolder($folder);
            return self::$SYNC_STATUS_DELTED;
        }

        $files = Array();
        set_time_limit(120);

        # Hole alle Dateien eines Verzeichnisses
        $dir=dir($folderpath);
        while ($elm = $dir->read())
        {
            if (is_file($folderpath.DIRECTORY_SEPARATOR.$elm))
                array_push($files, $elm);
        }

        # Lösche nicht mehr vorhandene Files eines Verzeichnisses aus der DB
        $query = $db->getQuery(true);
        $query->delete('#__eventgallery_file')
            ->where('folder='.$db->quote($folder))
            ->where('file not in (\''.implode('\',\'',$files).'\')');

        $db->setQuery($query);
        $db->execute();

        # Füge alle Dateien eines Verzeichnisses in die DB ein.
        foreach($files as $file)
        {
            $filepath = $folderpath.DIRECTORY_SEPARATOR.$file;
            @list($width, $height, $type, $attr) = getimagesize($filepath);

            $created = date('Y-m-d H:i:s',filemtime($filepath));

            $query = "insert IGNORE into #__eventgallery_file
					set folder=".$db->quote($folder).",
						file=".$db->quote($file).",
						width=".$db->quote($width).",
						height=".$db->quote($height).",
						published=1,
						created=".$db->quote($created).",
						modified=now(),
						userid=".$db->Quote($user->id)."
					;";
            $db->setQuery($query);
            $db->execute();

            EventgalleryController::updateMetadata($folderpath.DIRECTORY_SEPARATOR.$file, $folder, $file);
        }

        return self::$SYNC_STATUS_SYNC;
    }

    protected function deleteFolder($folder) {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->delete('#__eventgallery_folder')
            ->where('folder='.$db->quote($folder));
        $db->setQuery($query);
        $db->execute();

        $query = $db->getQuery(true);
        $query->delete('#__eventgallery_file')
            ->where('folder='.$db->quote($folder));
        $db->setQuery($query);

    }



}
