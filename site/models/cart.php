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
	var $_user_id     = null;
	
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
    function getCart() {
    	
    	if ($this->_cart == null) {
    		$this->_cart = parent::getItem();
    		if ($this->_cart == null) {
    			$this->_cart = $this->createCart();
    			$this->_cart = parent::getItem();
    		}

    	}		

    }

    /**
    * create a new cart
    */
    function createCart() {
    	$data = array('userid'=>$this->_user_id, 'table' => 'Cart');    	
    	$this->store($data);
    }

    /**
    * adds an image to the cart and checks if this action is actually allowed
    */

    function addItem($folder, $file, $count=1) {		

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

		/* security check END */
		
		$item = array('lineitemcontainerid'  => $this->_cart->id ,
									'folder' => $model->file->folder, 
									  'file' => $model->file->file, 
								  'quantity' => $count, 
								    'table'  => 'Imagelineitem');		
		
		
		$this->store($item);

    }

    function removeItem($lineitemid) {

    	if ($lineitemid==null) {
			return;
		}

		$model = JModelLegacy::getInstance('Lineitem', 'EventgalleryModels', $this->_cart->id);
		$model->removeItem($lineitemid);

		

    }

    function getLineItems() {
		$model = JModelLegacy::getInstance('Lineitem', 'EventgalleryModels', $this->_cart->id);
		return $model->getItems($this->_cart->id);    	

    }

	/**
	* returns the cart as a json object
	*/
    function getCartJSON() {

    	$jsonCart = array();

    	$model = JModelLegacy::getInstance('SingleImage', 'EventgalleryModel');

    	foreach($this->getLineItems() as $lineitem) {
    		$file = $this->getFile($lineitem->folder, $lineitem->file);
    		$imagetag = '<a class="thumbnail" 
    						href="'.$file->getImageUrl(null, null, true).'" 
    						title="'.htmlentities($file->getPlainTextTitle()).'" 
    						data-title="'.rawurlencode($file->getLightBoxTitle()).'" 
    						data-lineitem-id="'.$lineitem->id.'"
    						rel="lightbo2[cart]"> '.$file->getThumbImgTag(100,100).'</a>';

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
	


 
}
