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


class EventgalleryLibraryImagelineitem extends EventgalleryLibraryLineitem
{

    /**
     * @var EventgalleryLibraryFile
     */
    protected $_file = null;
    /**
     * @var EventgalleryLibraryImagetype
     */
    protected $_imagetype = null;
    /**
     * @var string
     */
    protected $_lineitem_dbtable = '#__eventgallery_imagelineitem';
    /**
     * @var string
     */
    protected $_lineitem_table = 'Imagelineitem';

    /**
     * creates the lineitem object. The given $lineitem can be an stdClass object or a id of a line item.
     * This is necessary since a lineitemcontainer can already preload it's line items with a single query.
     *
     * @param $lineitem
     */
    function __construct($lineitem)
    {
        parent::__construct($lineitem);


    }


    /**
     * @return string
     */
    public function getMiniCartThumb()
    {
        return $this->getFile()->getMiniCartThumb($this);
    }

    /**
     * @return string
     */
    public function getCartThumb()
    {
        return $this->getFile()->getCartThumb($this);
    }

    /**
     * @return EventgalleryLibraryFile|null
     */
    public function getFile()
    {
        if ($this->_file == null) {
            $this->_file = new EventgalleryLibraryFile($this->_lineitem->folder, $this->_lineitem->file);
        }
        return $this->_file;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->_lineitem->file;
    }

    /**
     * @return string
     */
    public function getFolderName()
    {
        return $this->_lineitem->folder;
    }

    /**
     * @return EventgalleryLibraryImagetype|null
     */
    public function getImageType()
    {
        if ($this->_imagetype == null) {
            $this->_imagetype = new EventgalleryLibraryImagetype($this->_lineitem->typeid);
        }

        return $this->_imagetype;
    }

    /**
     * @param int $imagetypeid
     *
     * @throws Exception
     */
    public function setImageType($imagetypeid)
    {
        $newImageType = $this->getFile()->getImageTypeSet()->getImageType($imagetypeid);
        /* @var $newImageType EventgalleryLibraryImagetype */
        if ($newImageType == null) {
            throw new Exception("The selected image type is invalid for this line item");
        }

        $this->_lineitem->typeid = $newImageType->getId();
        $this->_lineitem->singleprice = $newImageType->getPrice();
        $this->_store();
        $this->_imagetype = null;
    }

}
