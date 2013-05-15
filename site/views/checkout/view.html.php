<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');


class CheckoutViewCheckout extends JViewLegacy
{
	function display($tpl = null)
	{		

	    $app = JFactory::getApplication();	    


		$cart = JModelLegacy::getInstance('Cart', 'EventgalleryModel')->getCart();	


		$params	 = $app->getParams();
		$this->assign('cart', $cart);
		$this->assign('params', $params);
		
		$pathway = $app->getPathWay();		
		$pathway->addItem(JText::_('COM_EVENTGALLERY_CART_CHECKOUT_PATH'));
		
		$this->_prepareDocument();
		
		parent::display($tpl);
	}
	
		
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		

		$title = $this->params->get('page_title', '');

		$title .= " - ".JText::_('COM_EVENTGALLERY_CART_CHECKOUT_PATH');


		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		
		
		if ($this->document) {
				
			$this->document->setTitle($title);
			
		}
	}

}
?>