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

jimport( 'joomla.application.component.model' );
jimport('joomla.html.pagination');

//jimport( 'joomla.application.component.helper' );

class EventgalleryModelsCart extends EventgalleryModelsDefault
{
	protected $cart = null;
	protected $option = '';

	var $_cart = null;
	var $_lineitems = null;
	var $_user_id  = null;
	
	function __construct()
	{
	 	
		$app = JFactory::getApplication();
		 
		// store the variable that we would like to keep for next time
		// function syntax is setUserState( $key, $value );
		$this->option = $app->input->get('option');

		$session = JFactory::getSession();
		$this->_user_id = $session->getId();

		$this->getCart();
	    parent::__construct();	    
	 
	}

	/**
	* Builds the query to be used by the book model
	* @return   object  Query object
	*
	*
	*/
	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('c.*');
		$query->from('#__eventgallery_cart as c');

		return $query;
	}

	/**
	* Builds the filter for the query
	* @param    object  Query object
	* @return   object  Query object
	*
	*/
	protected function _buildWhere(&$query)
	{
		$db = JFactory::getDBO();
		$query->where('c.statusid is null');
		$query->where('c.userid = ' . $db->quote($this->_user_id) );

		return $query;
	}

	/**
	* get the cart from the database. 
	*/
    protected function getCart() {
    	
    	if ($this->_cart == null) {
    		$this->_cart = parent::getItem();
    		if ($this->_cart == null) {
    			$data = array('userid'=>$this->_user_id, 'table' => 'Cart');    	
    			$this->_cart = $this->store($data);
    		}
    	}	
    	return $this->_cart;	
    }

    /**
    * adds an image to the cart and checks if this action is actually allowed
    */

    function addItem($folder, $file, $count=1, $typeid = null) {		

		if ($file==null || $folder==null) {
			return;
		}


		
		$model = JModelLegacy::getInstance('SingleImage', 'EventgalleryModel');
		$model->getData($folder,$file);

		/* security check BEGIN */
		if (!$model->folder->cartable==1) {
			return;
		}

 		if (is_Object($model->folder) && strlen($model->folder->password)>0) {
	    	$session = JFactory::getSession();
			$unlockedFoldersJson = $session->get("eventgallery_unlockedFolders","");

			$unlockedFolders = array();
			if (strlen($unlockedFoldersJson)>0) {
				$unlockedFolders = json_decode($unlockedFoldersJson, true);
			}
			
			if (!in_array($model->folder->folder, $unlockedFolders)) {
				return;
			}			
		}

		$folderObject = $this->getFolder($model->folder->folder);

		/* check of the folder allows the type id. take the first type if not specific type was given. */
		$type = null;

		if ($typeid == null ) {
			$type = $folderObject->imagetypeset->getTypes()[0];		
		} else {
			$type = $folderObject->imagetypeset->getType($typeid);
		}

		if ($type == null) {
			return;
		}

		/* security check END */
		
		$item = array('lineitemcontainerid'  => $this->getCart()->id ,
									'folder' => $model->file->folder, 
									  'file' => $model->file->file, 
								  'quantity' => $count, 
								    'status' => 0,
								    'singleprice'  => $type->price,
								    'price'  => $count * $type->price,
								    'currency' => $type->currency,
								    'typeid' => $type->id,
								    'table'  => 'Imagelineitem');		
		
		$lineitem = $this->getItemByFileAndType($item);
		if ($lineitem != null) {
			$item['id'] = $lineitem->id;
			$item['quantity'] += $lineitem->quantity;
			$item['price'] = $item['quantity']*$item['singleprice'];
		}



		$this->store($item);

		$this->updateCart();

    }

    /**
    * updated the cart. Call this method if something changed and the cart/lineitems need to be refreshed.
    */
    function updateCart() {
    	$this->_cart = null;
    	$this->_lineitems = null;    	
    }

    /**
    * Deletes a line item
    */    
    function removeItem($lineitemid) {

    	if ($lineitemid==null) {
			return;
		}

		$model = JModelLegacy::getInstance('Lineitem', 'EventgalleryModels', $this->getCart()->id);
		$model->removeItem($lineitemid);

		$this->updateCart();
    }

    /**
    * returns all lineitems from this container
    */
    function getLineItems() {
    	if ($this->_lineitems == null) {
			$model = JModelLegacy::getInstance('Lineitem', 'EventgalleryModels', $this->getCart()->id);
			$this->_lineitems = $model->getItems($this->getCart()->id);    	
		}
		return $this->_lineitems;
    }

    /**
    * returns a lineitem with a specific id from this lineitemcontainer
    */
    function getLineItem($lineitemid) {
    	foreach($this->getLineItems() as $lineitem) {
    		if ($lineitemid == $lineitem->id) {
    			return $lineitem;
    		}
    	}
    }

    protected function getItemByFileAndType($data) {
    	$model = JModelLegacy::getInstance('Lineitem', 'EventgalleryModels', $this->getCart()->id);
    	return $model->getItemByFileAndType($data['lineitemcontainerid'], $data['folder'], $data['file'], $data['typeid']);
    }

	/**
	* returns the cart as a json object
	*/
    function getCartJSON() {

    	$jsonCart = array();

    	foreach($this->getLineItems() as $lineitem) {
    		$file = $this->getFile($lineitem->folder, $lineitem->file);
    		$imagetag = $file->getCartThumb($lineitem->id);

    		$item = array(	'file'=>$lineitem->file, 
    						'folder'=>$lineitem->folder, 
    						'count'=>$lineitem->quantity, 
    						'lineitemid'=>$lineitem->id,
    						'typeid'=>$lineitem->typeid,
    						'imagetag' => $imagetag);

    		array_push($jsonCart, $item);
    	}

    	return json_encode($jsonCart);
    }
	
	/* updates the quantity of an lineitem */
    function setLineItemQuantity($lineitemid, $quantity) {
    	$item = $this->getLineItem($lineitemid);
		$item->quantity = $quantity;
		$item->price = $item->quantity*$item->singleprice;
		$item->table='Imagelineitem';
		$this->store((array)$item);
		$this->updateCart();
    }

    /* Updates the type of a lineitem */
    function setLineItemType($lineitemid, $typeid) {
    	$item = $this->getLineItem($lineitemid);

    	$folderObject = $this->getFolder($item->folder);

		/* check of the folder allows the type id. take the first type if not specific type was given. */

		if ($typeid == null ) {
			return;	
		}

		$type = $folderObject->imagetypeset->getType($typeid);		

		if ($type == null) {
			return;
		}

		$item->typeid = $type->id;
		$item->singleprice = $type->price;
		$item->price = $item->quantity*$item->singleprice;
		$item->table='Imagelineitem';
		$this->store((array)$item);
		$this->updateCart();
    }

    /*
    * creates a order from a cart
    */
    function createOrder() {
    	$order = (array) $this->getCart();
    	$order['table'] = 'Order';
    	unset($order['id']);
    	unset($order['modified']);
    	unset($order['created']);
    	$order = $this->store($order);

    	foreach($this->getLineItems() as $lineitem) {
    		$data = (array)$lineitem;
    		$data['table'] = 'Imagelineitem';
    		$data['status'] = 1;
    		$data['lineitemcontainerid'] = $order->id;    		
    		$this->store($data);    		
    	}

    	$this->updateCart();
    }

 
}
