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

/**
 * @property mixed cart
 */
class EventgalleryLibraryCart extends EventgalleryLibraryLineitemcontainer
{

    /**
     * @var string
     */
    protected $_lineitemcontainer_table = "Cart";



    public function __construct($user_id = null)
    {
        $this->_user_id = $user_id;
        $this->_loadLineItemContainer();
        parent::__construct();
    }

    protected function _loadLineItemContainer()
    {

        $this->_lineitemcontainer = null;
        $this->_lineitems = null;

        $db = JFactory::getDBO();
        $query = $db->getQuery(TRUE);

        $query->select('c.*');
        $query->from('#__eventgallery_cart as c');
        $query->where('c.statusid is null');
        $query->where('c.userid = ' . $db->quote($this->_user_id));
        $db->setQuery($query);

        $this->_lineitemcontainer = $db->loadObject();

        if ($this->_lineitemcontainer == null) {
            $data = array('userid' => $this->_user_id, 'table' => $this->_lineitemcontainer_table);
            $this->_lineitemcontainer = parent::store($data);
        }

        $this->_loadLineItems();
    }



    /**
     * Updates the cart object stucture from the database
     */
    protected function _updateLineItemContainer()
    {
        $this->_loadLineItemContainer();

        // reset some objects since we change some things.
        $this->_surcharge = null;
        $this->_shipping = null;
        $this->_payment = null;
    }

    function cloneLineItem($lineitemid)
    {
        $lineitem = $this->getLineItem($lineitemid);


        // do not clone a not existing line item.
        if ($lineitem == null) {
            return;
        }

        $quantity = 1;
        $file = $lineitem->getFile();
        $imagetype = $file->getImageTypeSet()->getDefaultImageType();

        $item = array('lineitemcontainerid' => $this->getId(),
            'folder' => $file->getFolderName(),
            'file' => $file->getFileName(),
            'quantity' => $quantity,
            'status' => 0,
            'singleprice' => $imagetype->getPrice(),
            'price' => $quantity * $imagetype->getPrice(),
            'currency' => $imagetype->getCurrency(),
            'typeid' => $imagetype->getId(),
            'table' => 'Imagelineitem');

        $this->store($item);

        $this->_updateLineItemContainer();
    }

    public function storeCart()
    {
        $data = $this->_lineitemcontainer;
        $data->surchargeid = 1;
        $data->table = $this->_lineitemcontainer_table;
        $this->store((array)$data);
    }

    /**
     * adds an image to the cart and checks if this action is actually allowed
     */

    function addItem($foldername, $filename, $count = 1, $typeid = null)
    {

        if ($filename == null || $foldername == null) {
            throw new Exception("can't add item with invalid file or folder name");
        }

        $file = new EventgalleryLibraryFile($foldername, $filename);

        /* security check BEGIN */
        if (!$file->isCartable()) {
            throw new Exception("the item you try to add is not cartable.");
        }

        if (!$file->isPublished()) {
            throw new Exception("the item you try to add is not published.");
        }

        if (!$file->isAccessible()) {
            throw new Exception("the item you try to add is not accessible. You might need to enter a password to unlock the folder first.");
        }

        /* check of the folder allows the type id. take the first type if not specific type was given. */

        /*@var EventgalleryLibraryImagetype */
        $imageType = null;

        if ($typeid == null) {
            $imageType = $file->getImageTypeSet()->getDefaultImageType();
        } else {
            $imageType = $file->getImageTypeSet()->getImageType($typeid);
        }

        if ($imageType == null) {
            throw new Exception("the image type you specified for the new item is invalid. Reason for this can be that there is not image type set, no image type set image type assignments or the image type set does not contain the image type");
        }

        /* security check END */

        $item = array('lineitemcontainerid' => $this->getId(),
            'folder' => $file->getFolderName(),
            'file' => $file->getFileName(),
            'quantity' => $count,
            'status' => 0,
            'singleprice' => $imageType->getPrice(),
            'price' => $count * $imageType->getPrice(),
            'currency' => $imageType->getCurrency(),
            'typeid' => $imageType->getId(),
            'table' => 'Imagelineitem');

        $lineitem = $this->getLineItemByFileAndType($item['folder'], $item['file'], $item['typeid']);

        if ($lineitem != null) {
            $item['id'] = $lineitem->id;
            $item['quantity'] += $lineitem->quantity;
            $item['price'] = $item['quantity'] * $item['singleprice'];
        }

        $this->store($item);

        $this->_updateLineItemContainer();

    }

    /**
     * tries to find a line item in the database
     * @param $folder
     * @param $file
     * @param $typeid
     * @return stdClass
     */
    function getLineItemByFileAndType($folder, $file, $typeid)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(TRUE);
        $query->select('ili.*');
        $query->from('#__eventgallery_imagelineitem as ili');
        $query->where('ili.lineitemcontainerid=' . $db->quote($this->getId()));
        $query->where('ili.folder=' . $db->quote($folder));
        $query->where('ili.file=' . $db->quote($file));
        $query->where('ili.typeid=' . $db->quote($typeid));
        $db->setQuery($query);
        $item = $db->loadObject();
        return $item;
    }

}
