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

/**
 * This interface provides a general interface for all methods. All methods are configured using the database.
 *
 * Class EventgalleryLibraryInterfaceMethod
 */
interface EventgalleryLibraryInterfaceMethod
{
    /**
     * Returns the id of the current method.
     *
     * @return string the id
     */
    public function getId();

    /**
     * Returns the price of the method
     *
     * @return float the price value
     */
    public function getPrice();

    /**
     * Returns the currency of the price of the method
     *
     * @return string the currency
     */
    public function getCurrency();

    /**
     * Returns the name of the method
     *
     * @return string display name
     */
    public function getName();

    /**
     * Returns the display name of the method for the current locale
     *
     * @return string display name
     */
    public function getDisplayName();

    /**
     * Returns the description of the method for the current locale
     *
     * @return string display name
     */
    public function getDescription();

    /**
     * returns true if this is the default method.
     *
     * @return bool
     */
    public function isDefault();

    /**
     * Returns an object containing the data of a method.
     *
     * @return mixed|null
     */
    public function getData();

    /**
     * calculates the included tax.
     *
     *
     * returns the amount of tax for this item
     *
     * @return float
     */
    public function getTax();

    /**
     * Return the tax rate. 100 ==  100%
     *
     * @return int
     */
    public function getTaxrate();

    /**
     * Returns if this method can be used with the current cart.
     *
     * @param EventgalleryLibraryLineitemcontainer $cart
     *
     * @return bool
     */
    public function isEligible($cart);


    /**
     * returns the type code of this method
     *
     * @return int
     */
    public function getTypeCode();


    /**
     *
     * Event which is called if an order is about to be submitted
     *
     * @param $lineitemcontainer EventgalleryLibraryLineitemcontainer
     *
     * @return bool|array true or array with errors
     */
    public function processOnOrderSubmit($lineitemcontainer);

    /**
     * is called if there is an incoming request from an external system for a payment method.
     *
     * @return
     */
    public function onIncomingExternalRequest();

}
