<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_stats
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

 <script type="text/javascript">
        /* <![CDATA[ */
        window.addEvent("domready", function () {

            var options = {
                buttonShowType: 'inline',
                emptyCartSelector: '.eventgallery-cart-module-empty',
                cartSelector: '.eventgallery-ajaxcart-module',
                cartItemContainerSelector: '.eventgallery-ajaxcart-module .cart-items-container',
                cartItemsSelector: '.eventgallery-ajaxcart-module .cart-items',
                cartItemSelector: '.eventgallery-ajaxcart-module .cart-items .cart-item',
                cartCountSelector: '.eventgallery-ajaxcart-module .itemscount',
                buttonDownSelector: '.eventgallery-ajaxcart-module .toggle-down',
                buttonUpSelector: '.eventgallery-ajaxcart-module .toggle-up',
                'removeUrl': "<?php echo JRoute::_("index.php?option=com_eventgallery&view=rest&task=removefromcart&format=raw", true); ?>".replace(/&amp;/g, '&'),
                'add2cartUrl': "<?php echo JRoute::_("index.php?option=com_eventgallery&view=rest&task=add2cart&format=raw", true); ?>".replace(/&amp;/g, '&'),
                'removeLinkTitle': "<?php echo JText::_('MOD_EVENTGALLERY_CART_ITEM_REMOVE')?>",
                'getCartUrl': "<?php echo JRoute::_("index.php?option=com_eventgallery&view=rest&task=getCart&format=raw", true); ?>".replace(/&amp;/g, '&')
            };

           var eventgalleryCart = new EventgalleryCart(options);

        });
        /* ]]> */
    </script>

    <div class="eventgallery-ajaxcart eventgallery-ajaxcart-module">

        <div class="cart-items-container">
            <div class="cart-items"></div>
        </div>

        <div class="cart-summary btn-group">
            <button class="btn"><span class="itemscount">0</span> <?php echo JText::_('MOD_EVENTGALLERY_CART_ITEMS') ?>
            </button>
            <button title="<?php echo JText::_('MOD_EVENTGALLERY_CART_ITEMS_TOGGLE_DOWN') ?>" class="btn toggle-down" href="#"><i class="icon-arrow-down"></i></button>
            <button title="<?php echo JText::_('MOD_EVENTGALLERY_CART_ITEMS_TOGGLE_UP') ?>" class="btn toggle-up" href="#"><i class="icon-arrow-up"></i></button>
            <button onclick="document.location.href='<?php echo JRoute::_(
                "index.php?option=MOD_eventgallery&view=cart"
            ); ?>'" class="btn btn-primary"><?php echo JText::_('MOD_EVENTGALLERY_CART_BUTTON_CART') ?></button>
            <button class="btn" data-rel="lightbo2" data-href="#mb_cart-help-module">?</button>
        </div>
        <div style="display:none">
            <div id="mb_cart-help-module">
                <h2><?php echo JText::_('MOD_EVENTGALLERY_CART_HELP_HEADLINE') ?></h2>
                <?php echo JText::_('MOD_EVENTGALLERY_CART_HELP_TEXT') ?>
            </div>
        </div>
        <div style="clear:both"></div>

    </div>
    <div class="eventgallery-empty-cart eventgallery-cart-module-empty">
        <?php echo JText::_('COM_EVENTGALLERY_CART_EMPTY') ?>
    </div>
