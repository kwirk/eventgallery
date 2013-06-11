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

    protected $_lineitem = null;
    protected $_lineitem_id = null;
    protected $_lineitem_table = 'Imagelineitem';
    protected $_file = null;
    protected $_imagetype = null;

    /**
     * creates the lineitem object. The given $lineitem can be an stdClass object or a id of a line item.
     * This is necessary since a lineitemcontainer can already preload it's line items with a single query.
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

    public function getFile()
    {
        return $this->_file;
    }

    public function getFileName()
    {
        return $this->_lineitem->file;
    }

    public function getFolderName()
    {
        return $this->_lineitem->folder;
    }

    public function getQuantity()
    {
        return $this->_lineitem->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->_lineitem->quantity = $quantity;
        $this->_store;
    }

    public function getImageType()
    {
        return $this->_imagetype;
    }

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

    protected function _store()
    {
        $this->_lineitem->table = $this->_lineitem_table;
        $this->_lineitem->price = $this->_lineitem->singleprice * $this->_lineitem->quantity;
        parent::store((array)$this->_lineitem);
    }

    public function getCartThumb()
    {
        return $this->_file->getCartThumb($this->getId());
    }

    public function getId()
    {
        return $this->_lineitem->id;
    }

    public function getPrice() {
        return $this->_lineitem->price;
    }

    public function getSinglePrice() {
        return $this->_lineitem->singleprice;
    }

    public function getCurrency() {
        return $this->_lineitem->currency;
    }

    public function delete()
    {
        $db = JFactory::getDBO();
        $query = "delete from #__eventgallery_imagelineitem where id=" . $db->quote($this->getId()) . " and lineitemcontainerid=" . $db->quote($this->getLineItemContainerId()) . "";
        $db->setQuery($query);
        $db->execute();
    }

    public function getLineItemContainerId()
    {
        return $this->_lineitem->lineitemcontainerid;
    }

}
