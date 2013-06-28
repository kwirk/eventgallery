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

    protected $_lineitemstatus = EventgalleryLibraryLineitem::TYPE_CART;
    /**
     * @var TableOrder
     */
    protected $_lineitemcontainer = NULL;
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

        $this->_lineitemcontainer = NULL;
        $this->_lineitems = NULL;

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('o.*');
        $query->from('#__eventgallery_order as o');
        $query->where('o.id = ' . $db->quote($this->_lineitemcontainer_id));
        $db->setQuery($query);

        $this->_lineitemcontainer = $db->loadObject();

        if ($this->_lineitemcontainer == NULL) {
            throw new Exception("no order found for id " . $this->_lineitemcontainer_id);
        }

        $this->_loadLineItems();
        $this->_loadServiceLineItems();
    }

    /**
     * @param EventgalleryLibraryOrderStatus $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        if ($orderStatus == NULL) {
            return;
        }
        $this->_lineitemcontainer->orderstatusid = $orderStatus->getId();
        $this->_storeLineItemContainer();
    }

    /**
     * @return int
     */
    public function getPaymentStatus() {
        return $this->_lineitemcontainer->paymentstatusid;
    }

    /**
     * @param int $paymentstatus
     */
    public function setPaymentStatus($paymentstatus) {
        $this->_lineitemcontainer->paymentstatusid = $paymentstatus;
        $this->_storeLineItemContainer();
    }




}
