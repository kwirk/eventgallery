<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 * @var EventgalleryLibraryManagerOrder $orderMgr
 */
$orderMgr = EventgalleryLibraryManagerOrder::getInstance();
$orders = $orderMgr->getOrders();

$order = null;
foreach($orders as $myorder) {
    $order = $myorder;
    break;
}

?>

<div class="eventgallery-checkout">

    <h1>Your order with the id <?php echo $order->getDocumentNumber() ?> has been placed.</h1>

    <?php $this->set('edit',false); $this->set('lineitemcontainer', $order); echo $this->loadSnippet('checkout/summary') ?>
	<div class="clearfix"></div>
</div>



