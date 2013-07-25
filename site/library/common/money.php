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
 * provides a money object which handled amount and currency.
 *
 * Class EventgalleryLibraryCommonMoney
 */
class EventgalleryLibraryCommonMoney
{

    protected $_amount;
    protected $_currency;

    /**
     * @param float $amount
     * @param string $currency
     */
    public function __construct($amount, $currency)
    {
        $this->_amount=$amount;
        $this->_currency=$currency;
    }


    /**
     * @return string
     */
    public function __toString() {
        return $this->getCurrency().' '.sprintf("%.2f",$this->getAmount());
    }


    /**
     * @return float
     */
    public function getAmount() {
        return $this->_amount;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->_currency;
    }
}