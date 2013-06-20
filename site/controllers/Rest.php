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
     * @param bool  $cachable
     * @param array $urlparams
     *
     * @return JControllerLegacy|void
     */
    public function display($cachable = false, $urlparams = array())
    {
        parent::display($cachable, $urlparams);
    }

    /**
     * adds an item to the cart
     */
    public function add2cart()
    {


        $file = JRequest::getString('file', NULL);
        $folder = JRequest::getString('folder', NULL);
        $quantity = JRequest::getString('quantity', 1);
        $imagetypeid = JRequest::getString('imagetypeid', NULL);

        $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        $cart->addItem($folder, $file, $quantity, $imagetypeid);
        EventgalleryLibraryManagerCart::getInstance()->calculateCart();
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
            /* @var $lineitem EventgalleryLibraryImagelineitem */
            $item = array(
                'file' => $lineitem->getFileName(),
                'folder' => $lineitem->getfolderName(),
                'count' => $lineitem->getQuantity(),
                'lineitemid' => $lineitem->getId(),
                'typeid' => $lineitem->getImageType()->getId(),
                'imagetag' => $lineitem->getCartThumb()
            );

            array_push($jsonCart, $item);
        }


        echo json_encode($jsonCart);
    }

    public function getCart()
    {
        $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        $this->printCartJSON($cart);
    }

    /**
     * removes an item from the cart
     */
    public function removeFromCart()
    {


        $lineitemid = JRequest::getString('lineitemid', NULL);

        $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        $cart->deleteLineItem($lineitemid);
        EventgalleryLibraryManagerCart::getInstance()->calculateCart();

        $this->printCartJSON($cart);
    }

}
