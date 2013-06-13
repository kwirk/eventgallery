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
 * @property mixed cart
 */
abstract class EventgalleryLibraryLineitemcontainer extends EventgalleryLibraryDatabaseObject
{

    /**
     * @var string
     */
    protected $_user_id = null;
    /**
     * @var TableCart
     */
    protected $_lineitemcontainer = null;
    /**
     * @var array
     */
    protected $_lineitems = null;
    /**
     * @var EventgalleryLibrarySurcharge
     */
    protected $_surcharge = null;
    /**
     * @var EventGalleryLibraryShipping
     */
    protected $_shipping = null;
    /**
     * @var EventGalleryLibraryPayment
     */
    protected $_payment = null;
    /**
     * @var string
     */
    protected $_lineitemcontainer_table = null;

    public function __construct()
    {
        parent::__construct();
    }

    protected abstract function _loadLineItemContainer();

     /**
     * loads lineitems from the database
    *
     * @param int $lineitemstatus
     */
    protected function _loadLineItems($lineitemstatus=0)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(TRUE);
        $query->select('ili.*');
        $query->from('#__eventgallery_imagelineitem as ili');
        $query->where('ili.lineitemcontainerid = ' . $db->quote($this->getId()));
        $query->where('ili.status = '.$db->quote($lineitemstatus));
        $query->order('ili.id');

        $db->setQuery($query);

        $dbLineItems = $db->loadObjectList();

        $lineitems = array();
        foreach ($dbLineItems as $dbLineItem) {
            $lineitems[$dbLineItem->id] = new EventgalleryLibraryLineitem($dbLineItem);
        }

        $this->_lineitems = $lineitems;
    }

    public function getId()
    {
        return $this->_lineitemcontainer->id;
    }

    /**
     * @return int returns the current number of line items in this cart
     */
    function getLineItemsCount()
    {
        return count($this->_lineitems);
    }

    /**
     * @return int the sum of all quantities in this cart
     */
    function getLineItemsTotalCount()
    {
        $count = 0;
        /* @var EventgalleryLibraryLineitem $lineitem */
        foreach ($this->getLineItems() as $lineitem) {

            $count += $lineitem->getQuantity();
        }
        return $count;
    }

    /**
     * @return array all lineitems from this container
     */
    function getLineItems()
    {
        return $this->_lineitems;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->_lineitemcontainer->userid;
    }

    /**
     * @param string $currency
     */
    public function setSubTotalCurrency($currency)
    {
        $this->_lineitemcontainer->subtotalcurrency = $currency;
    }

    /**
     * returns a lineitem with a specific id from this lineitemcontainer
     */

    /**
     * @param float $price
     */
    public function setSubTotal($price)
    {
        $this->_lineitemcontainer->subtotal = $price;
    }

    /**
     * @return float
     */
    public function getSubTotal()
    {
        return $this->_lineitemcontainer->subtotal;
    }

    /**
     * @return string
     */
    public function getSubTotalCurrency()
    {
        return $this->_lineitemcontainer->subtotalcurrency;
    }

    /**
     * @param float $price
     */
    public function setTotal($price)
    {
        $this->_lineitemcontainer->total = $price;
    }

    /**
     * @return float
     */
    public function getTotal()
    {

        return $this->_lineitemcontainer->total;
    }

    /**
     * @param string $currency
     */
    public function setTotalCurrency($currency)
    {
        $this->_lineitemcontainer->totalcurrency = $currency;
    }

    /**
     * @return float
     */
    public function getTotalCurrency()
    {
        return $this->_lineitemcontainer->totalcurrency;
    }

    /**
     * @return EventgalleryLibrarySurcharge|null
     */
    public function getSurcharge()
    {
        if ($this->_lineitemcontainer->surchargeid == null) {
            return null;
        }

        if (!isset($this->_surcharge)) {
            $this->_surcharge = new EventgalleryLibrarySurcharge($this->_lineitemcontainer->surchargeid);
        }
        return $this->_surcharge;
    }

    /**
     * sets a surcharge
     *
     * @param EventgalleryLibrarySurcharge $surcharge
     */
    public function setSurcharge($surcharge)
    {
        $this->_surcharge = $surcharge;
        $this->_lineitemcontainer->surchargeid = $surcharge->getId();
    }

    /**
     * @return EventgalleryLibraryShipping|null
     */
    public function getShipping()
    {
        if ($this->_lineitemcontainer->shippingmethodid == null) {
            return null;
        }

        if (!isset($this->_shipping)) {
            $this->_shipping = new EventgalleryLibraryShipping($this->_lineitemcontainer->shippingmethodid);
        }
        return $this->_shipping;
    }

    /**
     * sets a shipping
     *
     * @param EventgalleryLibraryShipping $shipping
     */
    public function setShipping($shipping)
    {
        $this->_shipping = $shipping;
        $this->_lineitemcontainer->shippingmethodid = $shipping->getId();
    }

    /**
     * @return EventgalleryLibrarySurcharge|null
     */
    public function getPayment()
    {
        if ($this->_lineitemcontainer->paymentmethodid == null) {
            return null;
        }

        if (!isset($this->_payment)) {
            $this->_payment = new EventgalleryLibraryPayment($this->_lineitemcontainer->paymentmethodid);
        }
        return $this->_payment;
    }

    /**
     * sets a Payment
     *
     * @param EventgalleryLibraryPayment $payment
     */
    public function setPayment($payment)
    {
        $this->_payment = $payment;
        $this->_lineitemcontainer->paymentmethodid = $payment->getId();
    }

    function deleteLineItem($lineitemid)
    {
        if ($lineitemid == null) {
            return;
        }

        if ($this->getLineItem($lineitemid) == null) {
            return;
        }

        $this->getLineItem($lineitemid)->delete();
        $this->_updateCart();
    }

    /**
     * @param $lineitemid
     * @return EventgalleryLibraryLineitem
     */
    public function getLineItem($lineitemid)
    {
        if (isset($this->_lineitems[$lineitemid])) {
            return $this->_lineitems[$lineitemid];
        } else {
            return null;
        }
    }

    /**
     * Updates the cart object stucture from the database
     */
    protected abstract function _updateLineItemContainer();

}
