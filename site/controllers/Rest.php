<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class RestController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{			
		parent::display($cachable, $urlparams);		
	}

	public function add2cart() {


		$file = JRequest::getString( 'file' , null );
		$folder = JRequest::getString( 'folder' , null );
		

		$cart = $this->getModel('Cart', 'EventgalleryModel');	
		$cart->addItem($folder, $file);
		

		$this->getCart();
		
	}

	public function getCart() {

		$cart = $this->getModel('Cart', 'EventgalleryModel');			

		print_r($cart->getCartJSON());		
	}

	public function removeFromCart() {

		$session = JFactory::getSession();
		$file = JRequest::getString( 'file' , null );
		$folder = JRequest::getString( 'folder' , null );
		
		$cart = $this->getModel('Cart', 'EventgalleryModel');	
		$cart->removeItem($folder, $file);
		
		$this->getCart();
	}
}
