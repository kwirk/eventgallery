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
class EventgalleryLibraryOrder extends EventgalleryLibraryLineitemcontainer
{
    /**
     * @var TableOrder
     */
    protected $_lineitemcontainer = null;
    /**
     * @var string
     */
    protected $_lineitemcontainer_table = "Order";

    public function __construct($orderid)
    {
        $this->_lineitemcontainer_id = $orderid;
        $this->_loadLineItemContainer();
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function _loadLineItemContainer()
    {

        $this->_lineitemcontainer = null;
        $this->_lineitems = null;

        $db = JFactory::getDBO();
        $query = $db->getQuery(TRUE);

        $query->select('o.*');
        $query->from('#__eventgallery_order as o');
        $query->where('o.id = ' . $db->quote($this->_lineitemcontainer_id));
        $db->setQuery($query);

        $this->_lineitemcontainer = $db->loadObject();

        if ($this->_lineitemcontainer == null) {
            throw new Exception("no order found for id ". $this->_lineitemcontainer_id);
        }

        $this->_loadLineItems(1);
    }

    /**
     * @param EventgalleryLibraryOrderStatus $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        if ($orderStatus == null) {
            return;
        }
        $this->_lineitemcontainer->orderstatusid = $orderStatus->getId();
        $this->_storeLineItemContainer();
    }


    /**
     * Updates the cart object stucture from the database
     */
    protected function _updateLineItemContainer()
    {
        $this->_loadLineItemContainer();

        // reset some objects since we change some things.
        $this->_surcharge = null;
        $this->_shipping = null;
        $this->_payment = null;
    }

    /**
     * Use this method never in your source code. This is only for managers.
     *
     * @return TableOrder
     */
    public function _getInternalDataObject() {
        return $this->_lineitemcontainer;
    }

    protected function _storeLineItemContainer()
    {
        $data = $this->_lineitemcontainer;
        $this->store((array)$data, $this->_lineitemcontainer_table);
    }



    /**
     * @return string
     */
    public function getEMail() {
        return $this->_lineitemcontainer->email;
    }

    /**
     * @param string $email
     */
    public function setEMail($email){
        $this->_lineitemcontainer->email = $email;
        $this->_storeLineItemContainer();
    }

    /**
     * sets a surcharge
     *
     * @param EventgalleryLibrarySurcharge $surcharge
     */
    public function setSurcharge($surcharge)
    {
        if ($surcharge==null) {
            return;
        }

        $this->_lineitemcontainer->surchargetotal = $surcharge->getPrice();
        $this->_lineitemcontainer->surchargetotalcurrency = $surcharge->getCurrency();

        parent::setSurcharge($surcharge);
    }

    /**
     * sets a shipping method
     *
     * @param EventgalleryLibraryShipping $shipping
     */
    public function setShipping($shipping)
    {
        if ($shipping==null) {
            return;
        }

        $this->_lineitemcontainer->shippingtotal = $shipping->getPrice();
        $this->_lineitemcontainer->shippingtotalcurrency = $shipping->getCurrency();

        parent::setShipping($shipping);

    }

    /**
     * sets a payment
     *
     * @param EventgalleryLibraryPayment $payment
     */
    public function setPayment($payment)
    {
        if ($payment==null) {
            return;
        }

        $this->_lineitemcontainer->paymenttotal = $payment->getPrice();
        $this->_lineitemcontainer->paymenttotalcurrency = $payment->getCurrency();

        parent::setPayment($payment);
    }


}
