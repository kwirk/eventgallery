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


class EventgalleryLibraryServicelineitem extends EventgalleryLibraryLineitem
{

    const TYPE_SHIPINGMETHOD = 1;
    const TYPE_PAYMENTMETHOD = 2;
    const TYPE_SURCHARGE = 3;

    protected $_data = null;

    /**
     * @var string
     */
    protected $_lineitem_dbtable = '#__eventgallery_servicelineitem';
    /**
     * @var string
     */
    protected $_lineitem_table = 'Servicelineitem';

    /**
     * creates the lineitem object. The given $lineitem can be an stdClass object or a id of a line item.
     * This is necessary since a lineitemcontainer can already preload it's line items with a single query.
     *
     * @param $lineitem
     */
    function __construct($lineitem)
    {
        parent::__construct($lineitem);
    }

    /**
     * @return EventgalleryLibraryMethodesPayment|EventgalleryLibraryMethodesShipping|EventgalleryLibraryMethodesSurcharge
     */
    public function getMethod() {

        if ($this->isPaymentMethod()) {
            return EventgalleryLibraryManagerPayment::getInstance()->getMethode($this->_lineitem->methodid, false);
        }

        if ($this->isShippingMethod()) {
            return EventgalleryLibraryManagerShipping::getInstance()->getMethode($this->_lineitem->methodid, false);
        }

        if ($this->isSurcharge()) {
            return EventgalleryLibraryManagerSurcharge::getInstance()->getMethode($this->_lineitem->methodid, false);
        }

        return null;

    }

    function getName() {
        return $this->_lineitem->name;
    }

    function getDisplayName() {
        return $this->getMethod()->getDisplayName();
    }

    function getDescription() {
        return $this->getMethod()->getDescription();
    }


    /**
     * @return bool
     */
    public function isShippingMethod() {
        return $this->_lineitem->type==self::TYPE_SHIPINGMETHOD?true:false;
    }

    /**
     * @return bool
     */
    public function isSurcharge() {
        return $this->_lineitem->type==self::TYPE_SURCHARGE?true:false;
    }

    /**
     * @return bool
     */
    public function isPaymentMethod() {
        return $this->_lineitem->type==self::TYPE_PAYMENTMETHOD?true:false;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        if (null == $this->_data) {
            $this->_data = json_decode($this->_lineitem->data);
        }

        return $this->_data;
    }


}
