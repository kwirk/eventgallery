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

class EventgalleryLibraryCart extends EventgalleryLibraryDatabaseObject
{

	protected $_user_id = null;
	protected $_cart = null;
	protected $_lineitems = null;

 	public function __construct($user_id = null)
	{		
		$this->_user_id = $user_id;
		$this->_loadCart();
	    parent::__construct();	    	 
	}

	public function getId() {
		return $this->_cart->id;
	}

	protected function _loadCart() {

		$this->_cart = null;
		$this->_lineitems = null;

		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('c.*');
		$query->from('#__eventgallery_cart as c');
		$query->where('c.statusid is null');
		$query->where('c.userid = ' . $db->quote($this->_user_id) );		
		$db->setQuery($query);

		$this->_cart = $db->loadObject();

		if ($this->_cart==null) {
			$data = array('userid'=>$this->_user_id, 'table' => 'Cart');    	
    		$this->_cart = $this->store($data);
		}

		$this->_loadLineItems();
	}

	protected function _loadLineItems() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->select('ili.*');
		$query->from('#__eventgallery_imagelineitem as ili');
		$query->where('ili.lineitemcontainerid = ' . $db->quote($this->getId()) );
		$query->where('ili.status = 0');
		$query->order('ili.id');

		$db->setQuery($query);

		$dbLineItems = $db->loadObjectList();

		$lineitems = array();
		foreach($dbLineItems as $dbLineItem) {
			$lineitems[$dbLineItem->id] = new EventgalleryLibraryLineitem($dbLineItem);
		}		

		$this->_lineitems = $lineitems;
	}

		/* find a specific line item*/
	function getItemByFileAndType($folder, $file, $typeid) {
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->select('ili.*');
		$query->from('#__eventgallery_imagelineitem as ili');
		$query->where('ili.lineitemcontainerid='. $db->quote($this->getId()));
		$query->where('ili.folder='. $db->quote($folder));
		$query->where('ili.file='. $db->quote($file));
		$query->where('ili.typeid='. $db->quote($typeid));
		$db->setQuery($query);
		$item = $db->loadObject();		
		return $item;
	}	


	protected function _updateCart() {		
		$this->_loadCart();		
	}

	 /**
    * returns all lineitems from this container
    */
    function getLineItems() {
		return $this->_lineitems;
    }

    /**
     * @return int returns the current number of line items in this cart
     */
    function getLineItemsCount() {
        return count($this->_lineitems);
    }

    /**
     * @return int the sum of all quantities in this cart
     */
    function getLineItemsTotalCount() {
        $count = 0;
        /* @var EventgalleryLibraryLineitem $lineitem */
        foreach($this->getLineItems() as $lineitem) {

            $count += $lineitem->getQuantity();
        }
        return $count;
    }

    /**
    * returns a lineitem with a specific id from this lineitemcontainer
    */
    public function getLineItem($lineitemid) {
    	if (isset($this->_lineitems[$lineitemid])) {
    		return $this->_lineitems[$lineitemid]; 
    	}
    	else {
    		return null;
    	}
    }

    public function getUserId() {
        return $this->_cart->userid;
    }

    public function getSubTotal() {
        $price = 0;
        foreach($this->getLineItems() as $lineitem) {

            $price += $lineitem->getPrice();
        }
        return $price;
    }

    public function getSubTotalCurrency() {
        return $this->_getCurrency();
    }

    public function getTotal() {
        return $this->getSubTotal();
    }

    public function getTotalCurrency() {
        return $this->_getCurrency();
    }

    private function _getCurrency() {
        if ($this->getLineItemsTotalCount()>0) {
            $items = array_values($this->getLineItems());
            return $items[0]->getCurrency();
        }
        return "";
    }

    function deleteLineItem($lineitemid) {
    	if ($lineitemid==null) {
			return;
		}

		if ($this->getLineItem($lineitemid) == null) {
			return;
		}

		$this->getLineItem($lineitemid)->delete();
		$this->_updateCart();
    }


    function cloneLineItem($lineitemid) {
        $lineitem = $this->getLineItem($lineitemid);


        // do not clone a not existing line item.
        if ($lineitem == null) {
            return;
        }

        $quantity = 1;
        $file = $lineitem->getFile();
        $imagetype = $file->getImageTypeSet()->getDefaultImageType();

        $item = array('lineitemcontainerid'  => $this->getId() ,
            'folder' => $file->getFolderName(),
            'file' => $file->getFileName(),
            'quantity' => $quantity,
            'status' => 0,
            'singleprice'  => $imagetype->getPrice(),
            'price'  => $quantity * $imagetype->getPrice(),
            'currency' => $imagetype->getCurrency(),
            'typeid' => $imagetype->getId(),
            'table'  => 'Imagelineitem');

        $this->store($item);

        $this->_updateCart();
    }

     /**
    * adds an image to the cart and checks if this action is actually allowed
    */

    function addItem($foldername, $filename, $count=1, $typeid = null) {		

		if ($filename==null || $foldername==null) {
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

		/* check of the folder allows the type id. take the first type if not specific type was given.

		*/
        /*@var EventgalleryLibraryImagetype $imageType */
		$imageType = null;

		if ($typeid == null ) {
			$imageType = $file->getImageTypeSet()->getDefaultImageType();
		} else {
			$imageType = $file->getImageTypeSet()->getImageType($typeid);
		}

		if ($imageType == null) {
			throw new Exception("the image type you specified for the new item is invalid. Reason for this can be that there is not image type set, no image type set image type assignments or the image type set does not contain the image type");
		}

		/* security check END */
		
		$item = array('lineitemcontainerid'  => $this->getId() ,
									'folder' => $file->getFolderName(), 
									  'file' => $file->getFileName(), 
								  'quantity' => $count, 
								    'status' => 0,
								    'singleprice'  => $imageType->getPrice(),
								    'price'  => $count * $imageType->getPrice(),
								    'currency' => $imageType->getCurrency(),
								    'typeid' => $imageType->getId(),
								    'table'  => 'Imagelineitem');		
		
		$lineitem = $this->getItemByFileAndType($item['folder'], $item['file'], $item['typeid']);

		if ($lineitem != null) {
			$item['id'] = $lineitem->id;
			$item['quantity'] += $lineitem->quantity;
			$item['price'] = $item['quantity']*$item['singleprice'];
		}
		
		$this->store($item);

		$this->_updateCart();

    }


}
