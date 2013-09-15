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


class EventgalleryLibraryFilePicasa extends EventgalleryLibraryFile
{

    public $_blank_script_path = 'components/com_eventgallery/media/images/blank.gif';

    public $image;
    public $thumbs;

    /**
     * @var EventgalleryLibraryFolderPicasa
     */
    protected $_folder;

    /**
     * creates the lineitem object. $dblineitem is the database object of this line item
     *
     * @param string $foldername
     * @param string $filename
     */
    function __construct($foldername, $filename = NULL)
    {
        if (is_object($foldername)) {
            $this->_file = $foldername;
        } else {
            parent::__construct($foldername, $filename);
        }
        if (isset($this->_file->height)) {
            $this->imageRatio = $this->_file->width / $this->_file->height;
        } else {
            $this->imageRatio = 1;
        }
    }

    /**
     * Loads the current file based on the given folder and file name
     */
    protected function _loadFile()
    {
        $fileObject = NULL;

        $album = $this->_folder->getAlbum();

        foreach ($album->photos as $photo) {

            if (strcmp($photo['file'], $this->_filename) == 0) {
                $this->_file = (object)$photo;
                break;
            }
        }
    }

    /**
     * @return EventgalleryLibraryFolderPicasa
     */
    public function getFolder() {
        return $this->_folder;
    }

    /**
     * @return bool
     */
    public function isCommentingAllowed() {
        return false;
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

        $showExif = $params->get('show_exif', '1') == '1';

        $caption = "";

        if (isset($this->_file->caption) && strlen($this->_file->caption) > 0) {
            $caption .= '<span class="img-caption img-caption-part1">' . nl2br(htmlspecialchars($this->_file->caption)) . '</span>';
        }

        if ($showExif && isset($this->_file->exif) && strlen($this->_file->exif->model) > 0 && strlen($this->_file->exif->focallength) > 0
            && strlen($this->_file->exif->fstop) > 0
        ) {
            $exif = '<span class="img-exif">' . $this->_file->exif->model . ", " . $this->_file->exif->focallength . " mm, f/"
                . $this->_file->exif->fstop . ", ISO " . $this->_file->exif->iso . "</span>";
            if (!strpos($caption, "::")) {
                $caption .= "::";
            }
            $caption .= $exif;
        }

        return $caption;
    }


    public function getFullImgTag($width = 104, $height = 104)
    {


        if ($this->imageRatio >= 1) {
            $height = round($width / $this->imageRatio);
        } else {
            $width = round($height * $this->imageRatio);
        }
        // css verschiebung berechnen

        return '<img src="'.JURI::root().$this->_blank_script_path.'"
                     width="'.$width.'"
                     height="'.$height.'"
    				 style="width: '.$width.'px;
                            height: '.$height.'px;
                            background-repeat:no-repeat;
    						background-image:url(\'' . $this->getThumbUrl($width, $height, true, false) . '\');
    						background-position: 50% 50%;"
    						alt="" />';
    }

    public function getThumbImgTag($width = 104, $height = 104, $cssClass = "", $crop = false)
    {

        return '<img class="' . $cssClass . '"
    				 src="'.JURI::root().$this->_blank_script_path.'"
                     width="'.$width.'"
                     height="'.$height.'"
    				 style="width: '.$width.'px;
                            height: '.$height.'px;
                            background-repeat:no-repeat;
    						background-image:url(\'' . $this->getThumbUrl($width, $height, true, $crop) . '\');
    						background-position: 50% 50%;
							filter: progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\'' . $this->getThumbUrl(
            $width, $height, true, $crop
        ) . '\', sizingMethod=\'scale\');
							-ms-filter: &qout;progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''
        . $this->getThumbUrl($width, $height, true, $crop) . '\', sizingMethod=\'scale\')&quot;;
							"
    				 alt="" />';
    }

    public function getLazyThumbImgTag($width = 104, $height = 104, $cssClass = "", $crop = false)
    {

        $imgTag = '<img class="lazyme ' . $cssClass . '"
										data-width="' . $this->_file->width . '"
										data-height="' . $this->_file->height . '"
								    	longdesc="' . $this->getThumbUrl($width, $height, true, $crop) . '"
								    	src="'.JURI::root().$this->_blank_script_path.'"
                                        width="'.$width.'"
                                        height="'.$height.'"
								    	style=" width: '.$width.'px;
                                                height: '.$height.'px;
                                                background-position: 50% 50%;
                                                background-repeat:no-repeat;"
										alt=""
					    			/>';

        return $imgTag;

    }

    public function getImageUrl($width = 104, $height = 104, $fullsize, $larger = false)
    {
        if ($fullsize) {
            return $this->_file->image;
        } else {
            if ($this->imageRatio < 1) {
                return $this->getThumbUrl($height * $this->imageRatio, $height, $larger);
            } else {
                return $this->getThumbUrl($width, $width / $this->imageRatio, $larger);
            }
        }
    }

    public function getThumbUrl($width = 104, $height = 104, $larger = true, $crop = false)
    {

        if ($width == 0) {
            $width = 104;
        }
        if ($height == 0) {
            $height = 104;
        }


        if ($this->_file->width > $this->_file->height) {
            // querformat
            $googlewidth = $width;
            $resultingHeight = $googlewidth / $this->imageRatio;
            if ($resultingHeight < $height) {
                $googlewidth = round($height * $this->imageRatio);
            }
        } else {
            //hochformat
            $googlewidth = $height;
            $resultingWidth = $googlewidth * $this->imageRatio;
            if ($resultingWidth < $width) {
                $googlewidth = round($height / $this->imageRatio);
            }
        }


        $sizeSet = new EventgalleryHelpersSizeset();
        $saveAsSize = $sizeSet->getMatchingSize($googlewidth);

        //modify google image url
        $values = array_values($this->_file->thumbs);
        $winner = str_replace('/s104/', "/s$saveAsSize/", $values[0]);

        return $winner;
    }

    public function getOriginalImageUrl() {
        return $this->getImageUrl(600, 600, true);
    }

}
