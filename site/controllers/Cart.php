<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class CartController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams  = array())
	{			
		parent::display($cachable, $urlparams);		
	}

    public function cloneLineItem() {
        $lineitemid = JRequest::getString('lineitemid', null);
        /* @var EventgalleryLibraryCart $cart */
        $cart = EventgalleryLibraryManagerCart::getCart();
        $lineitem = $cart->getLineItem($lineitemid);
        if ($lineitem != null) {
            $cart->cloneLineItem($lineitemid);
        }

        EventgalleryLibraryManagerCart::calculateCart();

        $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=cart"));
    }

    public function updateCart() {

        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        EventgalleryLibraryManagerCart::updateCart();

        $continue = JRequest::getString( 'continue' , null );

        if ($continue == null) {
            $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=cart"));
        } else {
            $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout"));
        }
    }



}