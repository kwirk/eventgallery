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

<div class="eventgallery-cart">
    <h1><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ITEMS_IN_YOUR_CART') ?></h1>
    <?php echo JText::_('COM_EVENTGALLERY_CART_TEXT') ?>
    <form action="<?php echo JRoute::_("index.php?option=com_eventgallery&view=cart&task=updateCart") ?>" method="post"
          class="form-validate form-horizontal cart-form">
        <div class="cart-items">
            <table class="table table-hover">
                <tr>
                    <th>&nbsp;</th>
                    <th class="quantity"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_QUANTITY') ?></th>
                    <th class="imagetype"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_IMAGETYPE') ?></th>
                    <th class="price"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_PRICE') ?></th>
                </tr>
                <?php foreach ($this->cart->getLineItems() as $lineitem) :
                    /** @var EventgalleryLibraryImagelineitem $lineitem */ ?>
                    <tr id="row_<?php echo $lineitem->getId() ?>" class="cart-item">
                        <td class="image">
                            <?php echo $lineitem->getCartThumb($lineitem->getId()); ?>
                        </td>
                        <td class="quantity">
                            <input class="validate-numeric required input-small eventgallery-quantity" type="number"
                                   name="quantity_<?php echo $lineitem->getId() ?>"
                                   value="<?php echo $lineitem->getQuantity() ?>"/>
                            <a class="delete delete-lineitem" data-lineitemid="<?php echo $lineitem->getId() ?>"
                               href="#"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_DELETE') ?></a>
                            <a class="clone" href="<?php echo JRoute::_(
                                "index.php?option=com_eventgallery&view=cart&task=cloneLineItem&lineitemid="
                                . $lineitem->getId()
                            ); ?>"><?php echo JText::_('COM_EVENTGALLERY_LINEITEM_CLONE') ?></a>
                        </td>
                        <td class="imagetype">
                            <select class="required imagetype" name="type_<?php echo $lineitem->getId() ?>">
                                <?php
                                foreach ($lineitem->getFile()->getImageTypeSet()->getImageTypes() as $imageType) {
                                    /** @var EventgalleryLibraryImagetype $imageType */
                                    $selected = $lineitem->getImageType()->getId() == $imageType->getId()
                                        ? 'selected="selected"' : '';
                                    echo '<option ' . $selected . ' value="' . $imageType->getId() . '">'
                                        . $imageType->getDisplayName() . ' (' . $imageType->getCurrency() . ' '
                                        . $imageType->getPrice() . ')' . '</option>';
                                }
                                ?>
                            </select>

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
                    <div class="surcharge-headline"><?php echo $this->cart->getShippingMethod()->getDisplayName(
                        ); ?></div>
				<span class="surcharge">
					<?php echo $this->cart->getShippingMethod()->getCurrency(); ?>
                    <?php echo $this->cart->getShippingMethod()->getPrice(); ?>
				</span>
                </div>
            <?php ENDIF ?>
            <?php IF ($this->cart->getPaymentMethod() != NULL): ?>
                <div class="surcharge">
                    <div class="surcharge-headline"><?php echo $this->cart->getPaymentMethod()->getDisplayName(
                        ); ?></div>
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
        <div class="needs-calculation" style="">
            <?php echo JText::_('COM_EVENTGALLERY_CART_RECALCULATE') ?>
        </div>

        <fieldset>

            <div class="form-actions">
                <input name="updateCart" type="submit" class="validate btn btn-warning eventgallery-removeAll pull-left"
                       value="<?php echo JText::_('COM_EVENTGALLERY_CART_FORM_REMOVE_ALL') ?>"/>
                <div class="btn-group pull-right">
                    <input name="updateCart" type="submit" class="validate btn eventgallery-update"
                           value="<?php echo JText::_('COM_EVENTGALLERY_CART_FORM_UPDATE') ?>"/>
                    <input name="continue" type="submit" class="validate btn btn-primary"
                           value="<?php echo JText::_('COM_EVENTGALLERY_CART_FORM_CONTINUE') ?>"/>
                </div>
                <div class="clearfix"></div>
            </div>
        </fieldset>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>

<script type="text/javascript">
    window.addEvent("domready", function () {

        // hide the recalc message
        new Fx.Slide($$('.needs-calculation')[0]).hide();

        // update the carts description once something changed
        var setNeedsCalculationMode = function (e) {

            $$(".cart-item td.price").fade('out');
            var cartSummary = $$(".cart-summary")[0];

            new Fx.Slide(cartSummary, {
                duration: 500,
                onComplete: function () {
                    var needsCalc = $$(".needs-calculation")[0];
                    new Fx.Slide(needsCalc, {
                        duration: 250
                    }).slideIn();
                }
            }).slideOut();
        }


        var removeItem = function (e) {
            e.stop();
            var lineitemid = $(e.target).get('data-lineitemid');
            var parent = $('row_' + lineitemid);


            var myRequest = new Request.JSON(
                {
                    url: "<?php echo JRoute::_("index.php?option=com_eventgallery&view=rest&task=removefromcart&format=raw", true); ?>".replace(/&amp;/g, '&'),
                    method: "POST",
                    data: 'lineitemid=' + lineitemid,
                    onComplete: function (response) {
                        if (response !== undefined) {
                            // Work on each cell
                            // http://jsfiddle.net/gNvvT/5/
                            parent.getChildren('td, th').each(function (cell) {

                                // Create a dummy div wrap on cell content!
                                // The magic is here!
                                var content = cell.get('html');
                                var wrap = new Element('div', { html: content });
                                wrap.setStyles({
                                    'margin': 0,
                                    'padding': 0,
                                    'overflow': 'hidden'
                                });
                                cell.empty().adopt(wrap);
                                new Fx.Slide(wrap, {
                                    duration: 500,
                                    onComplete: function () {
                                        parent.dispose();
                                    }
                                }).slideOut(); // Slide it!
                            });

                            setNeedsCalculationMode();

                        }

                    }.bind(this)
                }
            ).send();

            $(e.target).fade('out');

        }

        function removeAllItems() {
            $$("input.eventgallery-quantity").set('value',0);
            $$(".eventgallery-update")[0].fireEvent('click');
        }

        $$(".cart-item input").addEvent('change', setNeedsCalculationMode);
        $$(".cart-item select").addEvent('change', setNeedsCalculationMode);
        $$(".cart-item .delete-lineitem").addEvent('click', removeItem);
        $$(".eventgallery-removeAll").addEvent('click', removeAllItems);


    });
</script>
