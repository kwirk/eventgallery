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
    <?php echo $this->escape($this->lineitemcontainer->getEMail()) ?></p>
    <?php IF (strlen($this->lineitemcontainer->getPhone())>0):?>
    <p><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_PHONE_LABEL') ?></strong><br />
    <?php echo $this->escape($this->lineitemcontainer->getPhone()) ?></p>
    <?php ENDIF; ?>
    <?php IF (strlen($this->lineitemcontainer->getMessage())>0):?>
    <p><strong><?php echo JText::_('COM_EVENTGALLERY_CHECKOUT_USERDATA_MESSAGE_LABEL') ?></strong><br />
    <?php echo $this->escape($this->lineitemcontainer->getMessage()) ?></p>
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
        
        <?php foreach ($this->lineitemcontainer->getLineItems() as $lineitem) :
            /** @var EventgalleryLibraryImagelineitem $lineitem */
             ?>
            <tr class="cart-item">
                <td>
                    <div class="image">
                        <?php echo $lineitem->getCartThumb($lineitem->getId()); ?>
                    </div>
               
                    <span class="price">
                        <?php echo $lineitem->getCurrency(); ?>
                        <?php echo $lineitem->getPrice(); ?>
                    </span>
                
                    <div class="information">
                       <span class="quantity"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_QUANTITY') ?>: <?php echo $lineitem->getQuantity() ?></span>
                       
                        <p class="imagetype-details"> 
                            <span class="displayname"><?php echo $lineitem->getImageType()->getDisplayName() ?></span>
                            <span class="description"><?php echo $lineitem->getImageType()->getDescription() ?></span>
                            <span class="singleprice"><?php echo JText::sprintf('COM_EVENTGALLERY_LINEITEM_PRICE_PER_ITEM_WITH_PLACEHOLDER', $lineitem->getImageType()->getCurrency(), $lineitem->getImageType()->getPrice()) ?></span>
                        </p>
                    </div>

                    <div style="clear:both;"></div>
                           
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>

<?php echo $this->loadSnippet('checkout/total') ?>