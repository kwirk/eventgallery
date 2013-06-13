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


class EventgalleryLibraryLineitem extends EventgalleryLibraryDatabaseObject
{

    /**
     * @var TableImagelineitem
     */
    protected $_lineitem = null;
    /**
     * @var int
     */
    protected $_lineitem_id = null;

    /**
     * @var string
     */
    protected $_lineitem_table = 'Imagelineitem';

    /**
     * @var EventgalleryLibraryFile
     */
    protected $_file = null;

    /**
     * @var EventgalleryLibraryImagetype
     */
    protected $_imagetype = null;

    /**
     * creates the lineitem object. The given $lineitem can be an stdClass object or a id of a line item.
     * This is necessary since a lineitemcontainer can already preload it's line items with a single query.
     *
     * @param $lineitem
     */
    function __construct($lineitem)
    {
        if ($lineitem instanceof stdClass) {
            $this->_lineitem = $lineitem;
            $this->_lineitem_id = $lineitem->id;
        } else {
            $this->_imagetype_id = $lineitem;
            $this->_loadLineItem();
        }

        $this->_file = new EventgalleryLibraryFile($this->_lineitem->folder, $this->_lineitem->file);
        $this->_imagetype = new EventgalleryLibraryImagetype($this->_lineitem->typeid);

        parent::__construct();
    }

    /**
     * Loads the line item by id
     */
    protected function _loadLineItem()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__eventgallery_imagelineitem');
        $query->where('id=' . $db->Quote($this->_lineitem_id));
        $db->setQuery($query);
        $this->_lineitem = $db->loadObject();
        $this->_lineitem->table = $this->_lineitem_table;

    }

    /**
     * @return EventgalleryLibraryFile|null
     */
    public function getFile()
    {
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
     * @return int
     */
    public function getQuantity()
    {
        return $this->_lineitem->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->_lineitem->quantity = $quantity;
        $this->_store();
    }

    /**
     * @return EventgalleryLibraryImagetype|null
     */
    public function getImageType()
    {
        return $this->_imagetype;
    }

    /**
     * @param int $imagetypeid
     * @throws Exception
     */
    public function setImageType($imagetypeid)
    {
        $newImageType = $this->_file->getImageTypeSet()->getImageType($imagetypeid);
        /* @var $newImageType EventgalleryLibraryImagetype */
        if ($newImageType == null) {
            throw new Exception("The selected image type is invalid for this line item");
        }

        $this->_lineitem->typeid = $newImageType->getId();
        $this->_lineitem->singleprice = $newImageType->getPrice();
        $this->_store();
    }

    /**
     *
     */
    protected function _store()
    {
        $this->_lineitem->table = $this->_lineitem_table;
        $this->_lineitem->price = $this->_lineitem->singleprice * $this->_lineitem->quantity;
        parent::store((array)$this->_lineitem);
    }

    /**
     * @return string
     */
    public function getCartThumb()
    {
        return $this->_file->getCartThumb($this->getId());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_lineitem->id;
    }

    /**
     * @return float
     */
    public function getPrice() {
        return $this->_lineitem->price;
    }

    /**
     * @return float
     */
    public function getSinglePrice() {
        return $this->_lineitem->singleprice;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->_lineitem->currency;
    }

    /**
     * deletes the current line item.
     */
    public function delete()
    {
        $db = JFactory::getDBO();
        $query = "delete from #__eventgallery_imagelineitem where id=" . $db->quote($this->getId()) . " and lineitemcontainerid=" . $db->quote($this->getLineItemContainerId()) . "";
        $db->setQuery($query);
        $db->execute();
    }

    /**
     * @return int
     */
    public function getLineItemContainerId()
    {
        return $this->_lineitem->lineitemcontainerid;
    }

}
