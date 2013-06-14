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

class EventgalleryLibraryManagerCart
{
	function __construct()
	{		   
	 
	}

	/**
	* get the cart from the database. 
	*/
    public static function getCart() {

    	/* try to get the right user id for the cart. This can also be the session id */
		$session = JFactory::getSession();
		$user_id = $session->getId();
    	
    	return new EventgalleryLibraryCart($user_id);
    }

    /**
     * This method tries to update the cart based on form values. Because of this you don't have to
     * provide anything this this method but the cart. If no cart is provided it takes the cart of
     * the current user.
     *
     * @param EventgalleryLibraryCart $cart
     */
    public static function updateCart(EventgalleryLibraryCart $cart = null) {

        if ($cart == null) {
            $cart = EventgalleryLibraryManagerCart::getCart();
        }

        /**
         * LINEITEM UPDATES
         */

        /* @var EventgalleryLibraryLineitem $lineitem */
        foreach($cart->getLineItems() as $lineitem){

            /* Quantity Update*/
            $quantity = JRequest::getString( 'quantity_'.$lineitem->getId() , null );
            if ($quantity != null) {

                if ($quantity>0) {
                    $lineitem->setQuantity($quantity);
                } else {
                    $cart->deleteLineItem($lineitem->getId());
                }
            }

            /* type update */

            $imagetypeid = JRequest::getString( 'type_'.$lineitem->getId() , null );

            if (null != $imagetypeid) {
                $lineitem->setImageType($imagetypeid);
            }

        }


        /**
         * SHIPPING UPDATE
         */

        $shippingmethodid = JRequest::getString( 'paymentid' , null );


        if ($shippingmethodid != null || $cart->getShipping()==null) {
            $shippingMgr = EventgalleryLibraryManagerShipping::getInstance();
            $methode = $shippingMgr->getMethode($shippingmethodid, true);
            if ($methode == null) {
                $methode = $shippingMgr->getDefaultMethode();
            }
            $cart->setShipping($methode);
        }

        /**
         * PAYMENT UPDATES
         */

        $paymentmethodid = JRequest::getString( 'paymentid' , null );


        if ($paymentmethodid != null || $cart->getPayment()==null) {
            $paymentMgr = EventgalleryLibraryManagerPayment::getInstance();
            $methode = $paymentMgr->getMethode($paymentmethodid, true);
            if ($methode == null) {
                $methode = $paymentMgr->getDefaultMethode();
            }
            $cart->setPayment($methode);
        }

        /**
         * USERDATA UPDATES
         */

        $phone = JRequest::getString( 'phone' , null );
        if ($phone != null) {
            $cart->setPhone($phone);
        }

        $email = JRequest::getString( 'email' , null );
        if ($email != null) {
            $cart->setEMail($email);
        }

        $message = JRequest::getString( 'message' , null );
        if ($message != null) {
            $cart->setMessage($message);
        }

        /**
         * ADDRESS UPDATE
         */

        $xmlPath = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_eventgallery' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR;

        $addressMgr = new EventgalleryLibraryManagerAddress();

        /**
         * @var JForm $billingform
         */
        $billingform = JForm::getInstance('billing',$xmlPath. 'billingaddress.xml');
        $billingform->bind(JRequest::get( 'post' ));
        $billingform->validate(JRequest::get( 'post' ));

        $billingdata = array();
        foreach($billingform->getFieldset() as $field) {
            $billingdata[$field->name] = $field->value;
        }

        $billingAddress = $cart->getBillingAddress();
        if ($billingAddress != null) {
            $billingdata['id'] = $billingAddress->getId();
        }

        $billingAddress =  $addressMgr->createStaticAddress($billingdata,'billing_');

        $cart->setBillingAddress($billingAddress);

        $shiptodifferentaddress = JRequest::getString( 'shiptodifferentaddress' , null);
        if ($shiptodifferentaddress=='true') {
            $shippingform = JForm::getInstance('shipping', $xmlPath . 'shippingaddress.xml');
            $shippingform->bind(JRequest::get('post'));

            $shippingdata = array();
            foreach ($shippingform->getFieldset() as $field) {
                $shippingdata[$field->name] = $field->value;
            }

            $shippingAddress = $cart->getShippingAddress();
            if ($shippingAddress != null && $shippingAddress->getId() != $billingAddress->getId()) {
                $shippingdata['id'] = $shippingAddress->getId();
            }

            $shippingAddress = $addressMgr->createStaticAddress($shippingdata, 'shipping_');

            $cart->setShippingAddress($shippingAddress);
        }
        elseif ($shiptodifferentaddress=='false'){
            $cart->setShippingAddress($billingAddress);
        }
        EventgalleryLibraryManagerCart::calculateCart();

    }

    public static function calculateCart() {
        $cart = EventgalleryLibraryManagerCart::getCart();

        // set subtotal;
        /**
         * @var  float $subtotal
         */
        $subtotal = 0;
        /**
         * @var EventgalleryLibraryLineitem $lineitem
         */

        $subtotalCurrency = "";

        foreach($cart->getLineItems() as $lineitem) {
            $subtotal += $lineitem->getPrice();
            $subtotalCurrency = $lineitem->getCurrency();
        }

        $cart->setSubTotal($subtotal);
        $cart->setSubTotalCurrency($subtotalCurrency);

        /* TODO: finish surcharge assignment */
        if ($cart->getSubTotal()>50) {
            $cart->setSurcharge(new EventgalleryLibrarySurcharge(2));
        } else {
            $cart->setSurcharge(new EventgalleryLibrarySurcharge(1));
        }
        /**
         * @var  float $total
         */
        $total = $subtotal;
        if ($cart->getSurcharge()!=null)  $total += $cart->getSurcharge()->getPrice();
        if ($cart->getShipping()!=null)  $total += $cart->getShipping()->getPrice();
        if ($cart->getPayment()!=null)  $total += $cart->getPayment()->getPrice();

        $cart->setTotal($total);
        $cart->setTotalCurrency($subtotalCurrency);


    }
 
}
