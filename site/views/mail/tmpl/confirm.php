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
 * @var EventgalleryLibraryOrder $order
 */

$order = $this->order;
?>



<h1>User</h1>
From: <?php echo $order->getBillingAddress()->getFirstName(). ' '. $order->getBillingAddress()->getLastName(); ?><br>
EMail: <?php echo $order->getEMail();?> <br>
Message: <?php echo $order->getMessage();?><br><br><br>


<h2>Billing Address</h2>

<?php echo $order->getBillingAddress()->getFirstName(); ?> <?php echo $order->getBillingAddress()->getLastName(); ?> <br/>
<?php echo $order->getBillingAddress()->getAddress1(); ?><br/>
<?php echo $order->getBillingAddress()->getAddress2(); ?><br/>
<?php echo $order->getBillingAddress()->getAddress3(); ?><br/>
<?php echo $order->getBillingAddress()->getZip(); ?> <?php echo $order->getBillingAddress()    ->getCity(); ?><br/>
<?php echo $order->getBillingAddress()->getCountry(); ?><br/>


<h2>Shipping Address</h2>

<?php echo $order->getShippingAddress()->getFirstName(); ?> <?php echo $order->getShippingAddress()->getLastName(); ?> <br/>
<?php echo $order->getShippingAddress()->getAddress1(); ?><br/>
<?php echo $order->getShippingAddress()->getAddress2(); ?><br/>
<?php echo $order->getShippingAddress()->getAddress3(); ?><br/>
<?php echo $order->getShippingAddress()->getZip(); ?> <?php echo $order->getShippingAddress()->getCity(); ?><br/>
<?php echo $order->getShippingAddress()->getCountry(); ?><br/>   

<h1>Your items</h1>


    <table>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_COUNT'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_PRICE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_IMAGETYPE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_FILE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_THUMBNAIL'); ?></th>

        <?php
        /**
         * @var EventgalleryLibraryImagelineitem $lineitem
         */
        FOREACH ($order->getLineItems() as $lineitem): ?>

            <tr><td>
            <?php echo $lineitem->getQuantity(); ?>
            </td><td>
            <?php echo $lineitem->getCurrency().' '.$lineitem->getPrice().' ( '.$lineitem->getCurrency().' '.$lineitem->getPrice() .')';?>
            </td><td>
            <?php echo $lineitem->getImageType()->getDisplayName(); ?>
            </td><td>
            <pre><?php echo $lineitem->getFolderName() .'/'. $lineitem->getFileName(); ?></pre>
            </td><td>



              <a class="thumbnail"
    						href="<?php echo $lineitem->getFile()->getImageUrl(NULL, NULL, true)?>"
    						> <?php echo $lineitem->getFile()->getThumbImgTag(100, 100)?></a>


            </td></tr>

        <?php ENDFOREACH; ?>
        </table>

        <strong>Subtotal: <?php echo $order->getSubTotalCurrency().' '. sprintf(
                "%0.2f", $order->getSubTotal()
            ); ?></strong>
        <br>

<?php IF ($order->getSurcharge() != NULL): ?>
    <?php echo $order->getSurcharge()->getDisplayName(); ?>
    <?php echo $order->getSurcharge()->getCurrency(); ?>
    <?php echo $order->getSurcharge()->getPrice(); ?><br>
<?php ENDIF ?>

<?php IF ($order->getShippingMethod() != NULL): ?>
    <?php echo $order->getShippingMethod()->getDisplayName(); ?>:
	<?php echo $order->getShippingMethod()->getCurrency(); ?>
    <?php echo $order->getShippingMethod()->getPrice(); ?><br>
<?php ENDIF ?>

<?php IF ($order->getPaymentMethod() != NULL): ?>
    <?php echo $order->getPaymentMethod()->getDisplayName(); ?>:
	<?php echo $order->getPaymentMethod()->getCurrency(); ?>
    <?php echo $order->getPaymentMethod()->getPrice(); ?><br>
<?php ENDIF ?>


<br>
    <strong>Total: <?php echo $order->getTotalCurrency().' '.sprintf("%0.2f", $order->getTotal()); ?></strong>
<br>
