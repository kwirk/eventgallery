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


abstract class EventgalleryLibraryFile implements EventgalleryLibraryInterfaceImage
{
    /**
     * @var string
     */
    protected $_filename = NULL;

    /**
     * @var string
     */
    protected $_foldername = NULL;

    /**
     * @var TableFile
     */
    protected $_file = NULL;

    /**
     * @var EventgalleryLibraryFolder
     */
    protected $_folder = NULL;

    /**
     * creates the lineitem object. $dblineitem is the database object of this line item
     *
     * @param string $foldername
     * @param string $filename
     */
    function __construct($foldername, $filename)
    {
        $this->_foldername = $foldername;
        $this->_filename = $filename;

        /**
         * @var EventgalleryLibraryManagerFolder $folderMgr
         */
        $folderMgr = EventgalleryLibraryManagerFolder::getInstance();

        $this->_folder = $folderMgr->getFolder($foldername);

        if ($this->_file == null) {
            $this->_loadFile();
        }

    }

    /**
     * loads the file from the database
     */
    abstract protected function _loadFile();


    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->_filename;
    }

    /**
     * @return string
     */
    public function getFolderName() {
        return $this->_foldername;
    }

    /**
     * @return EventgalleryLibraryFolder
     */
    public function getFolder() {
        return $this->_folder;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->getFolder()->isPublished() == 1 && $this->_file->published == 1;
    }


    /**
     * @return bool
     */
    public function isCommentingAllowed() {
        return $this->_file->allowcomments==1;
    }

    /**
     * checks if the image has a title to show.
     */
    public function hasTitle()
    {
        if (strlen($this->getTitle()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * returns the title of an image. Same as lightbox but without :: char.
     */
    public function getTitle()
    {
        return str_replace("::", "", $this->getLightBoxTitle());
    }

    public function getHeight() {
        return $this->_file->height;
    }

    public function getWidth() {
        return $this->_file->width;
    }

    /**
     *  returns a title with the following format:
     *
     *   <span class="img-caption img-caption-part1">Foo</span>[::<span class="img-caption img-caption-part1">Bar</span>][::<span class="img-exif">EXIF</span>]
     *
     *  :: is the separator for the lightbox to split in title and caption.
     */

    public function getLightBoxTitle()
    {

        $params = JComponentHelper::getParams('com_eventgallery');

        $showExif = $params->get('show_exif','1')=='1';

        $caption = "";

        if (isset($this->_file->title) && strlen($this->_file->title) > 0) {
            $caption .= '<span class="img-caption img-caption-part1">' . $this->_file->title . '</span>';
        }

        if (isset($this->_file->caption) && strlen($this->_file->caption) > 0) {

            if (strlen($caption) > 0) {
                $caption .= "::";
            }
            $caption .= '<span class="img-caption img-caption-part2">' . $this->_file->caption . '</span>';

        }

        if ($showExif && isset($this->exif) && isset($this->exif->model)>0 && isset($this->exif->focallength)>0 && isset($this->exif->fstop)>0) {
            $exif = '<span class="img-exif">'.$this->exif->model.", ".$this->exif->focallength. "mm, f/".$this->exif->fstop.", ISO ".$this->exif->iso."</span>";
            if (!strpos($caption, "::")) {
                $caption .= "::";
            }
            $caption .= $exif;
        }


        return $caption;
    }

    public function getCartThumb($lineitem)
    {
        return '<a class="thumbnail"
    						href="' . $this->getImageUrl(NULL, NULL, true) . '"
    						title="' . htmlentities($lineitem->getImageType()->getDisplayName()) . '"
    						data-title="' . rawurlencode($this->getLightBoxTitle()) . '"
    						data-lineitem-id="' . $lineitem->getId() . '"
    						rel="lightbo2[cart]"> ' . $this->getThumbImgTag(48, 48) . '</a>';
    }

    public function getMiniCartThumb($lineitem)
    {
        return '<a class="thumbnail"
    						href="' . $this->getImageUrl(NULL, NULL, true) . '"
    						title="' . htmlentities($lineitem->getImageType()->getDisplayName()) . '"
    						data-title="' . rawurlencode($this->getLightBoxTitle()) . '"
    						data-lineitem-id="' . $lineitem->getId() . '"
    						rel="lightbo2[cart]"> ' . $this->getThumbImgTag(48, 48) . '</a>';
    }

    /**
     * returns the title of an image. Returns the part before the :: only and strips out all tag elements
     */
    public function getPlainTextTitle()
    {

        if (isset($this->_file->title)) {
            return strip_tags($this->_file->title);
        }

        if (isset($this->_file->caption)) {
            return strip_tags($this->_file->caption);
        }

        return "";
    }


    /**
     * counts a hit on this file.
     */
    public function countHit() {
        return;
    }

    /**
     * returns the number of hits for this file
     *
     * @return int
     */
    public function getHitCount() {
        if (isset($this->_file->hits)) {
            return $this->_file->hits;
        }
        return 0;
    }
}
