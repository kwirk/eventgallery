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
 *
 */
$order = $this->order;
?>
<style type="text/css">

    body {
        background-color: #DDD;
    }
    
    #content {
        background-color: white;
        width: 700px;
        width: 100%;
        border: 1px solid #EEE;
        padding: 20px;
        padding-top: 40px;
        margin: auto;
    }

    table {
        background-color: white;
        width: 100%;
    }

    h1 {
        font-size: 1.2em;
    }

    h2 {
        font-size: 1.1em;
    }
    
    table {
        border-spacing: 0px;
    }
    
    table td{
        padding: 10px 10px 10px 0px;
    }
    
    .table-address {
        width: 100%;
    }

    .table-summary {
        width: 100%;
    }
    
    .table-summary td{
        text-align: right;
    }
    
    .table-summary .subtotal td{
        border-top: 1px solid silver;    
    }
    
    .table-summary .total td{
        border-top: 4px double silver;
        font-weight: bold;
    }
    
    .widerruf {
        width: 100%;
        border-top: 1px dashed silver;
        border-bottom: 1px dashed silver;
    }


</style>
<div id="content">
    <p>  
        <?php echo JText::sprintf('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_CONFIRMATION_HEADLINE', $order->getBillingAddress()->getFirstName(). ' '. $order->getBillingAddress()->getLastName()) ?>        
    </p>

    <p> 
        <?php echo JText::sprintf('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_CONFIRMATION_MESSAGE', JHTML::_('date', $order->getCreationDate()), $order->getDocumentNumber()) ?>
    </p>

    <h1><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_CONFIRMATION_YOUR_ITEMS') ?></h1>

    <table>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_COUNT'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_PRICE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_IMAGETYPE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_FILE'); ?></th>
        <th><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_THUMBNAIL'); ?></th>

        <?php
        /**
         *@var EventgalleryLibraryOrder $order
        * @var EventgalleryLibraryImagelineitem $lineitem
        */
        FOREACH ($order->getLineItems() as $lineitem): ?>

            <tr>
                <td>
                    <?php echo $lineitem->getQuantity(); ?>
                </td>
                <td>
                    <?php echo $lineitem->getCurrency().' '.$lineitem->getPrice().' ( '.$lineitem->getCurrency().' '.$lineitem->getSinglePrice() .')';?>
                </td>
                <td>
                    <?php echo $lineitem->getImageType()->getDisplayName(); ?>
                </td>
                <td>
                    <?php echo $lineitem->getFolderName() .'/'. $lineitem->getFileName(); ?>
                </td>
                <td>
                    <a class="thumbnail"    href="<?php echo $lineitem->getFile()->getImageUrl(NULL, NULL, true)?>">
                        <img src="<?php echo $lineitem->getFile()->getThumbUrl(104, 104); ?>">
                    </a>
                </td>
            </tr>

        <?php ENDFOREACH; ?>
    </table>

    <table class="table-summary">
        <tr class="subtotal">
            <td>
                <?php echo JText::_('COM_EVENTGALLERY_CART_SUBTOTAL') ?>
            </td>
            <td>
                <?php echo $order->getSubTotalCurrency().' '. sprintf("%0.2f", $order->getSubTotal()); ?>
            </td>
        </tr>
        <?php IF ($order->getSurcharge() != NULL): ?>
        <tr>
            <td>
                <?php echo $order->getSurcharge()->getDisplayName(); ?>
            </td>
            <td>
                <?php echo $order->getSurcharge()->getCurrency(); ?>
                <?php echo $order->getSurcharge()->getPrice(); ?>
            </td>
        </tr>
        <?php ENDIF ?>

        <?php IF ($order->getShippingMethod() != NULL): ?>
        <tr>
            <td>
                <?php echo $order->getShippingMethod()->getDisplayName(); ?>:
            </td>
            <td>
                <?php echo $order->getShippingMethod()->getCurrency(); ?>
                <?php echo $order->getShippingMethod()->getPrice(); ?>
            </td>
        </tr>
        <?php ENDIF ?>

        <?php IF ($order->getPaymentMethod() != NULL): ?>
        <tr>
            <td>
                <?php echo $order->getPaymentMethod()->getDisplayName(); ?>:
            </td>
            <td>
                <?php echo $order->getPaymentMethod()->getCurrency(); ?>
                <?php echo $order->getPaymentMethod()->getPrice(); ?>
            </td>
        </tr>
        <?php ENDIF ?>


        <tr class="total">
            <td>
                <?php echo JText::_('COM_EVENTGALLERY_CART_TOTAL') ?>:    
            </td>
            <td>
                <?php echo $order->getTotalCurrency().' '.sprintf("%0.2f", $order->getTotal()); ?>
            </td>
        </tr>
        
        <tr class="total">
            <td colspan="2">
               <?php echo JText::sprintf('COM_EVENTGALLERY_CART_VAT_HINT_WITH_PLACEHOLDER', $this->order->getTaxCurrency(), $this->order->getTax()) ?>  
            </td>
        </tr>

        
        
    </table>


    <table class="table-address">
        <tr>
            <td>            
                <h2><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_BILLINGADDRESS_HEADLINE') ?></h2>
                <?php $this->set('address',$order->getBillingAddress()); echo $this->loadSnippet('checkout/address') ?>    
            </td>
            <td>
                <h2><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_SHIPPINGADDRESS_HEADLINE') ?></h2>
                <?php $this->set('address',$order->getShippingAddress()); echo $this->loadSnippet('checkout/address') ?>
            </td>
        </tr>
    </table>


    <?php IF (strlen($order->getMessage())>0):?>
    <p><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_MESSAGE_LABEL') ?></strong></p>
    <p>
        <?php echo $order->getMessage();?>
    </p>
    <?php ENDIF; ?>
    
    
    <div class="widerruf">
        <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_CONFIRMATION_DISCLAIMER') ?>
       
    </div>
    
    <div class="contact">
        <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ORDER_MAIL_CONFIRMATION_MERCENTADDRESS') ?>
        
    </div>
    
</div>

