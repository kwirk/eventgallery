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
    protected static $_instance;
    protected $_carts = array();

    function __construct()
    {

    }

    public static function getInstance()
    {

        if (self::$_instance == NULL) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Updates line item quantities and types.
     *
     * Syntax:
     *  - quantity_[lineitemid]=[quantity}
     *  - type_[lineitemid]=[imagetypeid]
     *
     *
     * @param EventgalleryLibraryCart $cart
     *
     * @return array Errors
     */
    public function updateLineItems(EventgalleryLibraryCart $cart = NULL)
    {
        $errors = array();

        if ($cart == NULL) {
            $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        }

        /**
         * LINEITEM UPDATES
         */

        /* @var EventgalleryLibraryImagelineitem $lineitem */
        foreach ($cart->getLineItems() as $lineitem) {

            /* Quantity Update*/
            $quantity = JRequest::getString('quantity_' . $lineitem->getId(), NULL);
            if ($quantity != NULL) {

                if ($quantity > 0) {
                    $lineitem->setQuantity($quantity);
                } else {
                    $cart->deleteLineItem($lineitem->getId());
                }
            }

            /* type update */

            $imagetypeid = JRequest::getString('type_' . $lineitem->getId(), NULL);

            if (NULL != $imagetypeid) {
                $lineitem->setImageType($imagetypeid);
            }

        }

        return $errors;
    }

    /**
     * get the cart from the database.
     *
     * @return EventgalleryLibraryCart
     */
    public function getCart()
    {

        /* try to get the right user id for the cart. This can also be the session id */
        $session = JFactory::getSession();
        $user_id = $session->getId();
        if (!isset($this->_carts[$user_id])) {
            $this->_carts[$user_id] = new EventgalleryLibraryCart($user_id);
        }
        return $this->_carts[$user_id];
    }

    /**
     *
     * @param EventgalleryLibraryCart $cart
     *
     * @return array Errors
     */
    public function updateShippingMethod(EventgalleryLibraryCart $cart = NULL)
    {
        $errors = array();

        if ($cart == NULL) {
            $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        }


        /**
         * SHIPPING UPDATE
         */

        $shippingmethodid = JRequest::getString('shippingid', NULL);


        if ($shippingmethodid != NULL || $cart->getShippingMethod() == NULL) {
            $shippingMgr = EventgalleryLibraryManagerShipping::getInstance();
            $methode = $shippingMgr->getMethode($shippingmethodid, true);
            if ($methode == NULL) {
                $methode = $shippingMgr->getDefaultMethode();
            }
            $cart->setShippingMethod($methode);
        }

        return $errors;
    }

    /**
     *
     * @param EventgalleryLibraryCart $cart
     *
     * @return array Errors
     */
    public function updatePaymentMethod(EventgalleryLibraryCart $cart = NULL)
    {
        $errors = array();

        if ($cart == NULL) {
            $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        }

        /**
         * PAYMENT UPDATES
         */

        $paymentmethodid = JRequest::getString('paymentid', NULL);


        if ($paymentmethodid != NULL || $cart->getPaymentMethod() == NULL) {
            $paymentMgr = EventgalleryLibraryManagerPayment::getInstance();
            $methode = $paymentMgr->getMethode($paymentmethodid, true);
            if ($methode == NULL) {
                $methode = $paymentMgr->getDefaultMethode();
            }
            $cart->setPaymentMethod($methode);
        }

        return $errors;
    }

    /**
     * Updates the addresses of the cart
     *
     * validate billing address first. If this address is okay,
     * continue with the shipping address. This works for the customer
     * since there is also client side validation available
     *
     * @param EventgalleryLibraryCart $cart
     *
     * @return array Errors
     */
    public function updateAddresses(EventgalleryLibraryCart $cart = NULL)
    {
        $errors = array();

        if ($cart == NULL) {
            $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();
        }


        $xmlPath = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_eventgallery'
            . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR;

        /**
         * USERDATA UPDATES
         */

        $userdataform = JForm::getInstance('billing', $xmlPath . 'billingaddress.xml');
        $userdataform->bind(JRequest::get('post'));
        $userdatavalidation = $userdataform->validate(JRequest::get('post'));
        if ($userdatavalidation !== true) {
            $errors = array_merge($errors, $userdataform->getErrors());
        } else {

            $phone = JRequest::getString('phone', NULL);
            if ($phone != NULL) {
                $cart->setPhone($phone);
            }

            $email = JRequest::getString('email', NULL);
            if ($email != NULL) {
                $cart->setEMail($email);
            }

            $message = JRequest::getString('message', NULL);
            if ($message != NULL) {
                $cart->setMessage($message);
            }
        }

        /**
         * ADDRESS UPDATE
         *
         */


        $addressMgr = new EventgalleryLibraryManagerAddress();

        /**
         * @var JForm $billingform
         */
        $billingform = JForm::getInstance('billing', $xmlPath . 'billingaddress.xml');
        $billingform->bind(JRequest::get('post'));
        $billingvalidation = $billingform->validate(JRequest::get('post'));
        if ($billingvalidation !== true) {
            $errors = array_merge($errors, $billingform->getErrors());
        } else {

            $billingdata = array();
            foreach ($billingform->getFieldset() as $field) {
                $billingdata[$field->name] = $field->value;
            }

            /**
             * @var EventgalleryLibraryAddress $billingAddress
             */
            $billingAddress = $cart->getBillingAddress();
            if ($billingAddress != NULL) {
                $billingdata['id'] = $billingAddress->getId();
            }

            $billingAddress = $addressMgr->createStaticAddress($billingdata, 'billing_');

            $cart->setBillingAddress($billingAddress);


            $shiptodifferentaddress = JRequest::getString('shiptodifferentaddress', NULL);
            if ($shiptodifferentaddress == 'true') {
                /**
                 * @var JForm $shippingform
                 */
                $shippingform = JForm::getInstance('shipping', $xmlPath . 'shippingaddress.xml');
                $shippingform->bind(JRequest::get('post'));
                $shippingvalidation = $shippingform->validate(JRequest::get('post'));
                if ($shippingvalidation !== true) {
                    $errors = array_merge($errors, $shippingform->getErrors());
                } else {
                    $shippingdata = array();
                    foreach ($shippingform->getFieldset() as $field) {
                        $shippingdata[$field->name] = $field->value;
                    }

                    $shippingAddress = $cart->getShippingAddress();
                    if ($shippingAddress != NULL && $shippingAddress->getId() != $billingAddress->getId()) {
                        $shippingdata['id'] = $shippingAddress->getId();
                    }

                    /**
                     * @var EventgalleryLibraryAddress $shippingAddress
                     */
                    $shippingAddress = $addressMgr->createStaticAddress($shippingdata, 'shipping_');

                    $cart->setShippingAddress($shippingAddress);
                }
            } elseif ($shiptodifferentaddress == 'false') {
                $cart->setShippingAddress($billingAddress);
            }
        }

        return $errors;
    }

    public function calculateCart()
    {
        $cart = EventgalleryLibraryManagerCart::getInstance()->getCart();

        // set subtotal;
        /**
         * @var  float $subtotal
         */
        $subtotal = 0;
        /**
         * @var EventgalleryLibraryImagelineitem $lineitem
         */

        $subtotalCurrency = "";

        foreach ($cart->getLineItems() as $lineitem) {
            $subtotal += $lineitem->getPrice();
            $subtotalCurrency = $lineitem->getCurrency();
        }

        $cart->setSubTotal($subtotal);
        $cart->setSubTotalCurrency($subtotalCurrency);

        /* TODO: finish surcharge assignment */
        if ($cart->getSubTotal() > 50) {
            $cart->setSurcharge(EventgalleryLibraryManagerSurcharge::getInstance()->getMethode(2, true));
        } else {
            $cart->setSurcharge(EventgalleryLibraryManagerSurcharge::getInstance()->getMethode(1, true));
        }
        /**
         * @var  float $total
         */
        $total = $subtotal;
        if ($cart->getSurcharge() != NULL) {
            $total += $cart->getSurcharge()->getPrice();
        }
        if ($cart->getShippingMethod() != NULL) {
            $total += $cart->getShippingMethod()->getPrice();
        }
        if ($cart->getPaymentMethod() != NULL) {
            $total += $cart->getPaymentMethod()->getPrice();
        }

        $cart->setTotal($total);
        $cart->setTotalCurrency($subtotalCurrency);

    }

}
