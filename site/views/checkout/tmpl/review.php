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



<div class="eventgallery-checkout">
    <h1><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_HEADLINE') ?></h1>
    <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_TEXT') ?>&nbsp;
    <!--<a class="" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=cart") ?>"><?php echo JText::_('COM_EVENTGALLERY_CART')?> <i class="icon-arrow-right"></i></a>-->
    <form action="<?php echo JRoute::_("index.php?option=com_eventgallery&view=checkout&task=createOrder") ?>"
          method="post" class="form-validate form-horizontal checkout-form">
        <div class="cart-items">
            <table>
                <tr>
                    <th>&nbsp;</th>
                    <th class="quantity"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_QUANTITY') ?></th>
                    <th class="imagetype"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_IMAGETYPE') ?></th>
                    <th class="price"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_PRICE') ?></th>
                </tr>
                <?php foreach ($this->cart->getLineItems() as $lineitem) :
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

        <div class="cart-summary">
            <div class="subtotal">
                <div class="subtotal-headline"><?php echo JText::_('COM_EVENTGALLERY_CART_SUBTOTAL') ?></div>
				<span class="subtotal">
					<?php echo $this->cart->getSubTotalCurrency(); ?>
                    <?php printf("%.2f", $this->cart->getSubTotal()); ?>
				</span>
            </div>
            <?php IF ($this->cart->getSurcharge() != NULL): ?>

                <div class="surcharge">
                    <div class="surcharge-headline"><?php echo $this->cart->getSurcharge()->getDisplayName(); ?></div>
				<span class="surcharge">
					<?php echo $this->cart->getSurcharge()->getCurrency(); ?>
                    <?php echo $this->cart->getSurcharge()->getPrice(); ?>
				</span>
                </div>
            <?php ENDIF ?>
            <?php IF ($this->cart->getShippingMethod() != NULL): ?>
                <div class="surcharge">
                    <div class="surcharge-headline"><?php echo $this->cart->getShippingMethod()->getDisplayName(); ?>
                        <a href="<?php echo JRoute::_(
                            "index.php?option=com_eventgallery&view=checkout&task=change"
                        ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a></div>
				<span class="surcharge">
					<?php echo $this->cart->getShippingMethod()->getCurrency(); ?>
                    <?php echo $this->cart->getShippingMethod()->getPrice(); ?>
				</span>
                </div>
            <?php ENDIF ?>
            <?php IF ($this->cart->getPaymentMethod() != NULL): ?>
                <div class="surcharge">
                    <div class="surcharge-headline"><?php echo $this->cart->getPaymentMethod()->getDisplayName(); ?>
                        <a href="<?php echo JRoute::_(
                            "index.php?option=com_eventgallery&view=checkout&task=change"
                        ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a></div>
				<span class="surcharge">
					<?php echo $this->cart->getPaymentMethod()->getCurrency(); ?>
                    <?php echo $this->cart->getPaymentMethod()->getPrice(); ?>
				</span>
                </div>
            <?php ENDIF ?>
            <div class="total ">
                <div class="total-headline"><?php echo JText::_('COM_EVENTGALLERY_CART_TOTAL') ?></div>
				<span class="total">
					<?php echo $this->cart->getTotalCurrency(); ?>
                    <?php printf("%.2f", $this->cart->getTotal()); ?>
				</span>
				<span class="vat">
					<?php echo JText::_('COM_EVENTGALLERY_CART_VAT_HINT') ?>
				</span>
            </div>
        </div>


        <div class="review-billing-address">
            <h2><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_BILLINGADDRESS_HEADLINE') ?></h2>
            <?php echo $this->cart->getBillingAddress()->getFirstName(); ?> <?php echo $this->cart->getBillingAddress()
                ->getLastName(); ?> <br/>
            <?php echo $this->cart->getBillingAddress()->getAddress1(); ?><br/>
            <?php echo $this->cart->getBillingAddress()->getAddress2(); ?><br/>
            <?php echo $this->cart->getBillingAddress()->getAddress3(); ?><br/>
            <?php echo $this->cart->getBillingAddress()->getZip(); ?> <?php echo $this->cart->getBillingAddress()
                ->getCity(); ?><br/>
            <?php echo $this->cart->getBillingAddress()->getCountry(); ?><br/>
            <a href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=checkout&task=change"
            ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a>
        </div>

        <div class="review-shipping-address">
            <h2><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_SHIPPINGADDRESS_HEADLINE') ?></h2>
            <?php echo $this->cart->getShippingAddress()->getFirstName(); ?> <?php echo $this->cart->getShippingAddress(
            )->getLastName(); ?> <br/>
            <?php echo $this->cart->getShippingAddress()->getAddress1(); ?><br/>
            <?php echo $this->cart->getShippingAddress()->getAddress2(); ?><br/>
            <?php echo $this->cart->getShippingAddress()->getAddress3(); ?><br/>
            <?php echo $this->cart->getShippingAddress()->getZip(); ?> <?php echo $this->cart->getShippingAddress()
                ->getCity(); ?><br/>
            <?php echo $this->cart->getShippingAddress()->getCountry(); ?><br/>
            <a href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=checkout&task=change"
            ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a>
        </div>

        <div class="clearfix"></div>

        <fieldset>
            <div class="form-actions">
                <!--<input name="change" type="submit" class="validate btn" value="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE')?>"/>           -->
                <input name="continue" type="submit" class="validate btn btn-primary"
                       value="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CONTINUE') ?>"/>
            </div>
        </fieldset>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>



