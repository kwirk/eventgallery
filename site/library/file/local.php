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


class EventgalleryLibraryFileLocal extends EventgalleryLibraryFile
{


    /**
     * creates the lineitem object. $dblineitem is the database object of this line item
     *
     * @param string $foldername
     * @param string $filename
     */
    function __construct($foldername, $filename=null)
    {
        if (is_object($foldername) ) {
            $this->_file = $foldername;
            $foldername = $this->_file->folder;
            $filename = $this->_file->file;
        }
        parent::__construct($foldername, $filename);
        $app	 = JFactory::getApplication();
        $params = null;
        if ($app instanceof JSite) {
            /**
             * @var JSite $app
             */
            $params	 = $app->getParams();

        }else {
            /**
             * @var JAdministrator $app
             */
            $params = JComponentHelper::getParams('com_eventgallery');
        }

        if ($params->get('use_legacy_image_rendering','0')=='1') {
            $this->_image_script_path = "index.php";
        }


        if (isset($this->_file->exif) ){
            $this->exif = json_decode($this->_file->exif);
        }
        else {
            $this->exif = new stdClass();
        }
    }

    protected function _loadFile()
    {
        $fileObject = NULL;

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__eventgallery_file');
        $query->where('folder=' . $db->Quote($this->_foldername));
        $query->where('file=' . $db->Quote($this->_filename));
        $db->setQuery($query);
        $this->_file = $db->loadObject();

    }

    protected $_image_script_path = 'components/com_eventgallery/helpers/image.php';
    protected $_blank_script_path = 'components/com_eventgallery/media/images/blank.gif';
    public $exif;



    public function getFullImgTag($width=104,  $height=104) {

        return '<img src="'.JURI::root().$this->_blank_script_path.'"
	    				 width="'.$width.'"
	    				 height="'.$height.'"
	    	             style="width: '.$width.'px;
	    						height: '.$height.'px;
	    	             		background-repeat:no-repeat;
	    	    				background-position: 50% 50%;
	    	    				background-image:url(\''.$this->getThumbUrl($width,$height,false,true).'\');
	    	    				"
	    				alt="" />';

    }

    public function getThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {
        return '<img src="'.JURI::root().$this->_blank_script_path.'"
	    				 width="'.$width.'"
	    				 height="'.$height.'"
	    				 style="width: '.$width.'px;
	    						height: '.$height.'px;
	    						background-repeat:no-repeat;
	    						background-position: 50% 50%;
	    						background-image:url(\''.$this->getThumbUrl($width,$height, true, $height==$width).'\');
								filter: progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height, true, $height==$width).'\', sizingMethod=\'scale\');
								-ms-filter: &quot;progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\''.$this->getThumbUrl($width,$height, true, $height==$width).'\', sizingMethod=\'scale\')&quot;;


	    						"
	    				alt=""
	    				class="'.$cssClass.'"/>';
    }

    public function getLazyThumbImgTag($width=104,  $height=104, $cssClass="", $crop=false) {
        $imgTag = '<img class="lazyme '.$cssClass.'"
    									data-width="'.$this->_file->width.'"
										data-height="'.$this->_file->height.'"
								    	longdesc="'.$this->getThumbUrl($width,$height, true, $crop).'"
								    	src="'.JURI::root().$this->_blank_script_path.'"
								    	width="'.$width.'"
								    	height="'.$height.'"
								    	style=" width: '.$width.'px;
		    									height: '.$height.'px;
		    									background-position: 50% 50%;
		    									background-repeat: no-repeat;"
								    	alt=""
					    			/>';
        return $imgTag;
    }

    public function getImageUrl($width=104,  $height=104, $fullsize, $larger=false) {
        if ($fullsize) {
            return JURI::root().$this->_image_script_path."?option=com_eventgallery&mode=full&view=resizeimage&folder=".$this->getFolderName()."&file=".urlencode($this->getFileName());
        } else {

            if ($height>$width) {
                $width = $height;
            }

            return JURI::root().$this->_image_script_path."?option=com_eventgallery&width=".$width."&view=resizeimage&folder=".$this->getFolderName()."&file=".urlencode($this->getFileName());
        }
    }



    public function getThumbUrl ($width=104, $height=104, $larger=true, $crop=false) {

        if ($crop) {
            $mode = 'crop';
        } else {
            $mode = 'uncrop';
        }

        if ($height>$width) {
            $width = $height;
        }

        return JURI::root().$this->_image_script_path."?option=com_eventgallery&mode=".$mode."&width=".$width."&view=resizeimage&folder=".$this->getFolderName()."&file=".urlencode($this->getFileName());
    }

    /**
     * increases the hit counter in the database
     */
    public function countHit() {
        /**
         * @var TableFile $table
         */
        $table = JTable::getInstance('File', 'Table');


        $table->bind($this->_file);
        $table->hits++;
        $table->store();


    }

}
