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
    /**
     * @param bool $cachable
     * @param array $urlparams
     * @return JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams  = array())
    {
        parent::display($cachable, $urlparams);
    }

    /**
     * adds an item to the cart
     */
    public function add2cart()
    {

        $file = JRequest::getString('file', null);
        $folder = JRequest::getString('folder', null);
        $quantity = JRequest::getString('quantity', 1);
        $typeid = JRequest::getString('typeid', null);

        $cart = EventgalleryLibraryManagerCart::getCart();
        $cart->addItem($folder, $file, $quantity, $typeid);
        EventgalleryLibraryManagerCart::updateCart();
        $this->printCartJSON($cart);


    }

    /* returns the cart */

    /**
     * @param $cart - the cart object
     */
    protected function printCartJSON(EventgalleryLibraryCart $cart)
    {

        $jsonCart = array();
        foreach ($cart->getLineItems() as $lineitem) {
            /* @var $lineitem EventgalleryLibraryLineitem */
            $item = array('file' => $lineitem->getFileName(),
                'folder' => $lineitem->getfolderName(),
                'count' => $lineitem->getQuantity(),
                'lineitemid' => $lineitem->getId(),
                'typeid' => $lineitem->getImageType()->getId(),
                'imagetag' => $lineitem->getCartThumb());

            array_push($jsonCart, $item);
        }


        echo json_encode($jsonCart);
    }

    public function getCart()
    {
        $cart = EventgalleryLibraryManagerCart::getCart();
        $this->printCartJSON($cart);
    }

    /**
     * removes an item from the cart
     */
    public function removeFromCart()
    {


        $lineitemid = JRequest::getString('lineitemid', null);

        $cart = EventgalleryLibraryManagerCart::getCart();
        $cart->deleteLineItem($lineitemid);
        EventgalleryLibraryManagerCart::updateCart();

        $this->printCartJSON($cart);
    }

}
