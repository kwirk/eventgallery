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
            <?php echo $this->lineitemcontainer->getBillingAddress()->getFirstName(); ?> <?php echo $this->lineitemcontainer->getBillingAddress()
                ->getLastName(); ?> <br/>
            <?php echo $this->lineitemcontainer->getBillingAddress()->getAddress1(); ?><br/>
            <?php IF (strlen($this->lineitemcontainer->getBillingAddress()->getAddress2()>0)):?>
                <?php echo $this->lineitemcontainer->getBillingAddress()->getAddress2(); ?><br/>
            <?php ENDIF; ?>
            <?php IF (strlen($this->lineitemcontainer->getBillingAddress()->getAddress3()>0)):?>
                <?php echo $this->lineitemcontainer->getBillingAddress()->getAddress3(); ?><br/>
            <?php ENDIF; ?>
            <?php echo $this->lineitemcontainer->getBillingAddress()->getZip(); ?> <?php echo $this->lineitemcontainer->getBillingAddress()
                ->getCity(); ?><br/>
            <?php echo $this->lineitemcontainer->getBillingAddress()->getCountry(); ?><br/>
            <?php IF ($this->edit == true) :?>
            <a href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=checkout&task=change"
            ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a>
            <?php ENDIF ?>
        </div>

        <div class="review-shipping-address">
            <h2><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_SHIPPINGADDRESS_HEADLINE') ?></h2>
            <?php echo $this->lineitemcontainer->getShippingAddress()->getFirstName(); ?> <?php echo $this->lineitemcontainer->getShippingAddress(
            )->getLastName(); ?> <br/>
            <?php echo $this->lineitemcontainer->getShippingAddress()->getAddress1(); ?><br/>
            <?php IF (strlen($this->lineitemcontainer->getShippingAddress()->getAddress2()>0)):?>
                <?php echo $this->lineitemcontainer->getShippingAddress()->getAddress2(); ?><br/>
            <?php ENDIF; ?>
            <?php IF (strlen($this->lineitemcontainer->getShippingAddress()->getAddress3()>0)):?>
                <?php echo $this->lineitemcontainer->getShippingAddress()->getAddress3(); ?><br/>
            <?php ENDIF; ?>
            <?php echo $this->lineitemcontainer->getShippingAddress()->getZip(); ?> <?php echo $this->lineitemcontainer->getShippingAddress()
                ->getCity(); ?><br/>
            <?php echo $this->lineitemcontainer->getShippingAddress()->getCountry(); ?><br/>
            <?php IF ($this->edit == true) :?>
            <a href="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=checkout&task=change"
            ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a>
            <?php ENDIF ?>
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

        <div class="cart-summary">
            <div class="subtotal">
                <div class="subtotal-headline"><?php echo JText::_('COM_EVENTGALLERY_CART_SUBTOTAL') ?></div>
				<span class="subtotal">
					<?php echo $this->lineitemcontainer->getSubTotalCurrency(); ?>
                    <?php printf("%.2f", $this->lineitemcontainer->getSubTotal()); ?>
				</span>
            </div>
            <?php IF ($this->lineitemcontainer->getSurcharge() != NULL): ?>

                <div class="surcharge">
                    <div class="surcharge-headline"><?php echo $this->lineitemcontainer->getSurcharge()->getDisplayName(); ?></div>
				<span class="surcharge">
					<?php echo $this->lineitemcontainer->getSurcharge()->getCurrency(); ?>
                    <?php echo $this->lineitemcontainer->getSurcharge()->getPrice(); ?>
				</span>
                </div>
            <?php ENDIF ?>
            <?php IF ($this->lineitemcontainer->getShippingMethod() != NULL): ?>
                <div class="surcharge">
                    <div class="surcharge-headline"><?php echo $this->lineitemcontainer->getShippingMethod()->getDisplayName(); ?>
                        <?php IF ($this->edit == true) :?>
                            <a href="<?php echo JRoute::_(
                                "index.php?option=com_eventgallery&view=checkout&task=change"
                            ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a>
                        <?php ENDIF ?>;
                    </div>
				<span class="surcharge">
					<?php echo $this->lineitemcontainer->getShippingMethod()->getCurrency(); ?>
                    <?php echo $this->lineitemcontainer->getShippingMethod()->getPrice(); ?>
				</span>
                </div>
            <?php ENDIF ?>
            <?php IF ($this->lineitemcontainer->getPaymentMethod() != NULL): ?>
                <div class="surcharge">
                    <div class="surcharge-headline"><?php echo $this->lineitemcontainer->getPaymentMethod()->getDisplayName(); ?>
                        <?php IF ($this->edit == true) :?>
                            <a href="<?php echo JRoute::_(
                                "index.php?option=com_eventgallery&view=checkout&task=change"
                            ) ?>">(<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?>)</a>
                        <?php ENDIF ?>;
                    </div>
				<span class="surcharge">
					<?php echo $this->lineitemcontainer->getPaymentMethod()->getCurrency(); ?>
                    <?php echo $this->lineitemcontainer->getPaymentMethod()->getPrice(); ?>
				</span>
                </div>
            <?php ENDIF ?>
            <div class="total ">
                <div class="total-headline"><?php echo JText::_('COM_EVENTGALLERY_CART_TOTAL') ?></div>
				<span class="total">
					<?php echo $this->lineitemcontainer->getTotalCurrency(); ?>
                    <?php printf("%.2f", $this->lineitemcontainer->getTotal()); ?>
				</span>
				<span class="vat">
					<?php echo JText::sprintf('COM_EVENTGALLERY_CART_VAT_HINT_WITH_PLACEHOLDER', $this->lineitemcontainer->getTaxCurrency(), $this->lineitemcontainer->getTax()) ?>
				</span>
            </div>
        </div>




