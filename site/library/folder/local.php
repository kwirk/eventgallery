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

    protected static $_maindir = NULL;

    /**
     * $creates the folder object.
     */
    public function __construct($foldername)
    {
        parent::__construct($foldername);
    }

    /**
     * initializes the main directory for the local images
     */
    public static function setDir() {
        self::$_maindir = JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
    }


    /**
     * defines if this class can handle the given folder
     *
     * @param $folder
     */
    public static function canHandle($foldername) {

        if (strpos($foldername,'@' )== false) {
            return true;
        }

        return false;
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

    /**
     * syncs a local folder
     *
     * @param string $foldername
     * @return int|void
     */
    public static function syncFolder($foldername) {

        self::setDir();

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $folderpath = self::$_maindir.$foldername;
        if (!file_exists($folderpath)) {
            self::deleteFolder($foldername);
            return EventgalleryLibraryManagerFolder::$SYNC_STATUS_DELTED;
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
            ->where('folder='.$db->quote($foldername))
            ->where('file not in (\''.implode('\',\'',$files).'\')');

        $db->setQuery($query);
        $db->execute();

        # Füge alle Dateien eines Verzeichnisses in die DB ein.
        foreach($files as $file)
        {
            if ($file == 'index.html') {
                continue;
            }

            $filepath = $folderpath.DIRECTORY_SEPARATOR.$file;
            @list($width, $height, $type, $attr) = getimagesize($filepath);

            $created = date('Y-m-d H:i:s',filemtime($filepath));

            $query = "insert IGNORE into #__eventgallery_file
					set folder=".$db->quote($foldername).",
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

            self::updateMetadata($folderpath.DIRECTORY_SEPARATOR.$file, $foldername, $file);
        }

        return EventgalleryLibraryManagerFolder::$SYNC_STATUS_SYNC;
    }

    /**
     * Deletes a local folder
     *
     * @param $foldername string
     */
    protected static function deleteFolder($foldername) {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->delete('#__eventgallery_folder')
            ->where('folder='.$db->quote($foldername));
        $db->setQuery($query);
        $db->execute();

        $query = $db->getQuery(true);
        $query->delete('#__eventgallery_file')
            ->where('folder='.$db->quote($foldername));
        $db->setQuery($query);

    }

    /**
     * upaded meta information
     */
    public static function updateMetadata($path, $foldername, $filename) {

        $libPath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'pel'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
        require_once($libPath.'PelJpeg.php');
        require_once($libPath.'PelTiff.php');

        @list($width, $height, $type, $attr) = getimagesize($path);
        $exif = array();

        try {
            $input_jpeg = new PelJpeg($path);

            $app1 = $input_jpeg->getExif();

            if ($app1) {
                $tiff = $app1->getTiff();
                $ifd0 = $tiff->getIfd();
                $exifData = $ifd0->getSubIfd(PelIfd::EXIF);



                if ($exifData) {
                    if ($data = $exifData->getEntry(PelTag::APERTURE_VALUE)) {
                        $value = $data->getValue();
                        $exif['fstop'] = sprintf('%.01f',pow(2, $value[0]/$value[1]/2));
                    }
                    if ($data = $exifData->getEntry(PelTag::FOCAL_LENGTH)) {
                        $value = $data->getValue();
                        $exif['focallength'] = sprintf('%.0f',$value[0]/$value[1]);
                    }
                    if ($data = $ifd0->getEntry(PelTag::MODEL)) {
                        $exif['model'] = $data->getText();
                    }
                    if ($data = $exifData->getEntry(PelTag::ISO_SPEED_RATINGS)) {
                        $exif['iso'] = $data->getText();
                    }
                }


            }
        } catch (Exception $e) {

        }

        $exifJson = json_encode($exif);

        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->update("#__eventgallery_file");
        $query->set("width=".$db->quote($width));
        $query->set("height=".$db->quote($height));
        $query->set("exif=".$db->quote($exifJson));
        $query->where('folder='.$db->quote($foldername));
        $query->where('file='.$db->quote($filename));
        $db->setQuery($query);
        $db->execute();

        Pel::clearExceptions();
        unset($input_jpeg);

    }

    public static function addNewFolders() {

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $folders = Array();

        if (file_exists(self::$_maindir)) {
        $verzeichnis = dir(self::$_maindir);
        } else {
            return;
        }

        # Hole die verfügbaren Verzeichnisse
        while ($elm = $verzeichnis->read())
        { //sucht alle Verzeichnisse mit Bilder
            if (is_dir(self::$_maindir.$elm) && !preg_match("/\./",$elm) && !preg_match("/.cache/",$elm))
            {
                if (is_dir(self::$_maindir.$elm.DIRECTORY_SEPARATOR ))
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
            $created = date('Y-m-d H:i:s',filemtime(self::$_maindir.$folder));

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
            $db->execute();

        }
    }

    public static function getFileHandlerClassname() {
        return 'EventgalleryLibraryFileLocal';
    }

}
