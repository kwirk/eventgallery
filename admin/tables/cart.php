<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access');


class TableCart extends JTable
{

    public $id;
    public $userid;
    public $statusid;
    public $subtotal;
    public $subtotalcurrency;
    public $total;
    public $totalcurrency;
    public $surchargeid;
    public $paymentmethodid;
    public $shippingmethodid;
    public $message;
    public $modified;
    public $created;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct( &$db ) {
		parent::__construct('#__eventgallery_cart', 'id', $db);
	}
}