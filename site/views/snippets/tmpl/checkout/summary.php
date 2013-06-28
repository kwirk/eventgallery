<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

?>

<div class="basic-information">
    <p><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_EMAIL_LABEL') ?></strong><br />
    <?php echo $this->lineitemcontainer->getEMail() ?></p>
    <?php IF (strlen($this->lineitemcontainer->getPhone()>0)):?>
    <p><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_PHONE_LABEL') ?></strong><br />
    <?php echo $this->lineitemcontainer->getPhone() ?></p>
    <?php ENDIF; ?>
    <?php IF (strlen($this->lineitemcontainer->getMessage()>0)):?>
    <p><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_MESSAGE_LABEL') ?></strong><br />
    <?php echo $this->lineitemcontainer->getMessage() ?></p>
    <?php ENDIF; ?>
</div>

<div class="review-billing-address">
    <h2><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_BILLINGADDRESS_HEADLINE') ?></h2>
    <?php $this->set('address',$this->lineitemcontainer->getBillingAddress()); echo $this->loadSnippet('checkout/address') ?>    
</div>

<div class="review-shipping-address">
    <h2><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_SHIPPINGADDRESS_HEADLINE') ?></h2>
    <?php $this->set('address',$this->lineitemcontainer->getShippingAddress()); echo $this->loadSnippet('checkout/address') ?>    
</div>


<div class="cart-items">
    <table class="table table-hover">
        <tr>
            <th>&nbsp;</th>
            <th class="quantity"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_QUANTITY') ?></th>
            <th class="imagetype"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_IMAGETYPE') ?></th>
            <th class="price"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_PRICE') ?></th>
        </tr>
        <?php foreach ($this->lineitemcontainer->getLineItems() as $lineitem) :
            /** @var EventgalleryLibraryImagelineitem $lineitem */
             ?>
            <tr class="cart-item">
                <td class="image">
                    <?php echo $lineitem->getCartThumb($lineitem->getId()); ?>
                </td>
                <td class="quantity">
                    <?php echo $lineitem->getQuantity() ?>
                </td>
                <td class="imagetype">
                    <?php echo $lineitem->getImageType()->getDisplayName() .
                        ' (' .
                        $lineitem->getImageType()->getCurrency() .
                        ' ' .
                        $lineitem->getImageType()->getPrice()
                        . ')';
                    ?>
                </td>
                <td class="price">
                    <?php echo $lineitem->getCurrency(); ?>
                    <?php echo $lineitem->getPrice(); ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>

<?php echo $this->loadSnippet('checkout/total') ?>