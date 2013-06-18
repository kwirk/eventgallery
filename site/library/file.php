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
    /**
     * @var string
     */
    protected $_filename = NULL;
    /**
     * @var EventgalleryHelpersImageInterface
     */
    protected $_file = NULL;

    /**
     * creates the lineitem object. $dblineitem is the database object of this line item
     *
     * @param string $foldername
     * @param string $filename
     */
    function __construct($foldername, $filename)
    {
        parent::__construct($foldername);
        $this->_filename = $filename;
        $this->_loadFile();

    }

    /**
     * loads the file from the database
     */
    protected function _loadFile()
    {
        $fileObject = NULL;

        if (strpos($this->_foldername, '@') > -1) {
            $values = explode("@", $this->_foldername, 2);
            $folderObject = $this->_folder;
            $picasakey = $folderObject->picasakey;
            $album = EventgalleryHelpersImageHelper::picasaweb_ListAlbum($values[0], $values[1], $picasakey);

            foreach ($album->photos as $photo) {

                if (strcmp($photo->file, $this->_filename) == 0) {
                    $fileObject = new EventgalleryHelpersImagePicasa($photo);
                    break;
                }

            }

        } else {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__eventgallery_file');
            $query->where('folder=' . $db->Quote($this->_foldername));
            $query->where('file=' . $db->Quote($this->_filename));
            $db->setQuery($query);
            $result = $db->loadObject();
            $fileObject = new EventgalleryHelpersImageLocal($result);
        }

        /**
         * @var EventgalleryHelpersImageInterface $fileObject
         */
        $this->_file = $fileObject;

    }

    /**
     * @param int $lineitemid
     *
     * @return string
     */
    public function getCartThumb($lineitemid)
    {

        return $this->_file->getCartThumb($lineitemid);
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->_file->getFileName();
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->_folder->published == 1 && $this->_file->isPublished() == 1;
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    public function getFullImgTag($width = 104, $height = 104)
    {
        return $this->_file->getFullImgTag($width, $height);
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $cssClass
     * @param bool   $crop
     *
     * @return string
     */
    public function getThumbImgTag($width = 104, $height = 104, $cssClass = "", $crop = false)
    {
        return $this->_file->getThumbImgTag($width, $height, $cssClass, $crop);
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $cssClass
     * @param bool   $crop
     *
     * @return string
     */
    public function getLazyThumbImgTag($width = 104, $height = 104, $cssClass = "", $crop = false)
    {
        return $this->_file->getLazyThumbImgTag($width, $height, $cssClass, $crop);
    }

    /**
     * @param int  $width
     * @param int  $height
     * @param      $fullsize
     * @param bool $larger
     *
     * @return string
     */
    public function getImageUrl($width = 104, $height = 104, $fullsize, $larger = false)
    {
        return $this->_file->getImageUrl($width, $height, $fullsize, $larger);
    }

    /**
     * @param int  $width
     * @param int  $height
     * @param bool $larger
     * @param bool $crop
     *
     * @return string
     */
    public function getThumbUrl($width = 104, $height = 104, $larger = true, $crop = false)
    {
        return $this->_file->getThumbUrl($width, $height, $larger, $crop);
    }
}
