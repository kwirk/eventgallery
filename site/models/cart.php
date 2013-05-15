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

class EventgalleryModelCart extends JModelLegacy
{
	protected $cart = null;
	protected $option = '';
	
	function __construct()
	{
	 	
		$app = JFactory::getApplication();
		 
		// store the variable that we would like to keep for next time
		// function syntax is setUserState( $key, $value );
		$this->option = $app->input->get('option');

		$this->load();
	    parent::__construct();	    
	 
	}

    function getCart() {
    	
		return $this->cart;
    }


    function addItem($folder, $file, $count=1) {		

		if ($file==null || $folder==null) {
			return;
		}

		
		$model = JModelLegacy::getInstance('SingleImage', 'EventgalleryModel');
		$model->getData($folder,$file);

	
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
		
		$imagetag =  $model->file->getLazyThumbImgTag(100,100, "", true);


		$imagetag = '<a class="thumbnail" href="'.$model->file->getImageUrl(null, null, true).'" title="'.htmlentities($model->file->getPlainTextTitle()).'" data-title="'.rawurlencode($model->file->getLightBoxTitle()).'" rel="lightbo2[cart]"> '.$model->file->getThumbImgTag(100,100).'</a>';

		$item = array('file'=>$file, 'folder'=>$folder, 'count'=>1, 'imagetag' => $imagetag);

		if (!in_array($item, $this->cart)) {
			array_push($this->cart, $item);
		}
		
		$this->store();

    }

    function removeItem($folder, $file) {

    	if ($file==null || $folder==null) {
			return;
		}

		// do this to avoid getting an array like {1=>value, 2=>value} since the javascript
		// expects an array like {value, value}
		$newCart = array();
		foreach ($this->cart as $item) {
			if (strcmp($item['folder'],$folder)==0 && strcmp($item['file'],$file)==0) {
				
			} else {
				array_push($newCart, $item);
			}
		}	

		$this->cart = $newCart;
		$this->store();

    }

    function getCartJSON() {
    	
    	return json_encode($this->cart);
    }

	// loads the cart
    function load() {
    	$session = JFactory::getSession();
		$cartJson = $session->get($this->option.".cart","");

		$this->cart = array();
		if (strlen($cartJson)>0) {
			$this->cart = json_decode($cartJson, true);
		}

    }

    // saves te cart
    function store() {
    	$session = JFactory::getSession();
    	$session->set( $this->option.".cart", json_encode($this->cart) );
    }


        	
 
}
