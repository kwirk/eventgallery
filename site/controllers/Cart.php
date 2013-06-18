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
    public function display($cachable = false, $urlparams = array())
    {
        parent::display(false, $urlparams);
    }

    public function cloneLineItem()
    {
        $lineitemid = JRequest::getString('lineitemid', NULL);
        /* @var EventgalleryLibraryCart $cart */
        $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        $lineitem = $cart->getLineItem($lineitemid);
        if ($lineitem != NULL) {
            $cart->cloneLineItem($lineitemid);
        }

        EventgalleryLibraryManagerCart::getInstance()->calculateCart();

        $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=cart"));
    }

    /**
     * updates the cart
     */
    public function updateCart()
    {

        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        EventgalleryLibraryManagerCart::getInstance()->updateLineItems();
        EventgalleryLibraryManagerCart::getInstance()->calculateCart();

        $continue = JRequest::getString('continue', NULL);

        if ($continue == NULL) {
            $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=cart"));
        } else {
            $this->setRedirect(JRoute::_("index.php?option=com_eventgallery&view=checkout"));
        }
    }


}
