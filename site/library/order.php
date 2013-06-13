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



    public function storeOrder()
    {
        $data = $this->_lineitemcontainer;
        $data->table = $this->_lineitemcontainer_table;
        $this->store((array)$data);
    }



}
