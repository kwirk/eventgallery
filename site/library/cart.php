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
    protected $_lineitemstatus = EventgalleryLibraryLineitem::TYPE_ORDER;
    /**
     * @var string
     */
    protected $_lineitemcontainer_table = "Cart";

    public function __construct($user_id = NULL)
    {
        $this->_user_id = $user_id;
        $this->_loadLineItemContainer();
        parent::__construct();
    }

    protected function _loadLineItemContainer()
    {

        $this->_lineitemcontainer = NULL;
        $this->_lineitems = NULL;

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('c.*');
        $query->from('#__eventgallery_cart as c');
        $query->where('c.statusid is null');
        $query->where('c.userid = ' . $db->quote($this->_user_id));
        $db->setQuery($query);

        $this->_lineitemcontainer = $db->loadObject();

        if ($this->_lineitemcontainer == NULL) {

            $uuid = uniqid("", true);
            $uuid = base_convert($uuid,16,10);

            /**
             * @var TableCart $data
             */

            $query = $db->getQuery(true);
            $query->insert("#__eventgallery_cart");
            $query->set("id=".$db->quote($uuid));
            $db->setQuery($query);
            $db->execute();

            $data = JTable::getInstance('cart', 'Table');
            $data->userid = $this->_user_id;
            $data->id=$uuid;

            $this->_lineitemcontainer = $this->store((array)$data, 'Cart');

        }

        $this->_loadLineItems();
        $this->_loadServiceLineItems();
    }

    function cloneLineItem($lineitemid)
    {
        /**
         * @var EventgalleryLibraryImagelineitem $lineitem
         */
        $lineitem = $this->getLineItem($lineitemid);


        // do not clone a not existing line item.
        if ($lineitem == NULL) {
            return;
        }

        $quantity = 1;
        $file = $lineitem->getFile();
        $imagetype = $file->getImageTypeSet()->getDefaultImageType();

        $item = array(
            'lineitemcontainerid' => $this->getId(),
            'folder' => $file->getFolderName(),
            'file' => $file->getFileName(),
            'quantity' => $quantity,
            'singleprice' => $imagetype->getPrice(),
            'price' => $quantity * $imagetype->getPrice(),
            'currency' => $imagetype->getCurrency(),
            'typeid' => $imagetype->getId()
        );

        $this->store($item, 'Imagelineitem');

        $this->_updateLineItemContainer();
    }

    /**
     * adds an image to the cart and checks if this action is actually allowed
     */

    function addItem($foldername, $filename, $count = 1, $typeid = NULL)
    {

        if ($filename == NULL || $foldername == NULL) {
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
        $imageType = NULL;

        if ($typeid == NULL) {
            $imageType = $file->getImageTypeSet()->getDefaultImageType();
        } else {
            $imageType = $file->getImageTypeSet()->getImageType($typeid);
        }

        if ($imageType == NULL) {
            throw new Exception("the image type you specified for the new item is invalid. Reason for this can be that there is not image type set, no image type set image type assignments or the image type set does not contain the image type");
        }

        /* security check END */

        $item = array(
            'lineitemcontainerid' => $this->getId(),
            'folder' => $file->getFolderName(),
            'file' => $file->getFileName(),
            'quantity' => $count,
            'singleprice' => $imageType->getPrice(),
            'price' => $count * $imageType->getPrice(),
            'currency' => $imageType->getCurrency(),
            'typeid' => $imageType->getId()
        );

        $lineitem = $this->getLineItemByFileAndType($item['folder'], $item['file'], $item['typeid']);

        if ($lineitem != NULL) {
            $item['id'] = $lineitem->id;
            // $item['quantity'] += $lineitem->quantity;
            $item['price'] = $item['quantity'] * $item['singleprice'];
        }

        $this->store($item, 'Imagelineitem');

        $this->_updateLineItemContainer();

    }

    /**
     * tries to find a line item in the database
     *
     * @param $folder
     * @param $file
     * @param $typeid
     *
     * @return stdClass
     */
    public function getLineItemByFileAndType($folder, $file, $typeid)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
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

    /**
     * @param int $statusid
     */
    public function setStatus($statusid)
    {
        $this->_lineitemcontainer->statusid = $statusid;
        $this->_storeLineItemContainer();
    }

}
