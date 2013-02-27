<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access'); ?>

<?php 
	$myApp	 = &JFactory::getApplication();
	$myParams	 = &$myApp->getParams();
	$use_cart = !(strcmp('0',$myParams->get('use_cart','1'))==0);
?>

<?php IF($use_cart): ?>

	<script type="text/javascript">	
		window.addEvent("domready", function() {
			var options = {
				buttonShowType: 'inline',
				emptyCartSelector: '.eventgallery-cart-empty',
				cartSelector: '.eventgallery-cart',
				cartItemContainerSelector: '.cart-items-container',
				cartItemsSelector: '.eventgallery-cart .cart-items',
				cartCountSelector: '.itemscount',
				buttonDownSelector: '.toggle-down',
				buttonUpSelector: '.toggle-up',
				'removeUrl' :  "<?php echo JRoute::_("index.php?view=rest&task=removefromcart&format=raw"); ?>",
				'add2cartUrl' : "<?php echo JRoute::_("index.php?view=rest&task=add2cart&format=raw"); ?>",
				'removeLinkTitle' : "<?php echo JText::_('COM_EVENTGALLERY_CART_ITEM_REMOVE')?>",
				'getCartUrl' : "<?php echo JRoute::_("index.php?view=rest&task=getCart&format=raw"); ?>",
			};

			var eventgalleryCart = new EventgalleryCart(options);
			
		});
	</script>

	<div class="eventgallery-cart">

		<h2><?php echo JText::_('COM_EVENTGALLERY_CART')?></h2>

		<div class="cart-items-container">
			<div class="cart-items"></div>
		</div>
		
		<div class="cart-summary btn-group">
			<button class="btn"><span class="itemscount">0</span> <?php echo JText::_('COM_EVENTGALLERY_CART_ITEMS')?></button>
			<button class="btn toggle-down" href="#"><?php echo JText::_('COM_EVENTGALLERY_CART_ITEMS_TOGGLE_DOWN')?></button>
			<button class="btn toggle-up" href="#"><?php echo JText::_('COM_EVENTGALLERY_CART_ITEMS_TOGGLE_UP')?></button>	
			<button onclick="document.location.href='<?php echo JRoute::_("index.php?view=checkout");?>'" class="btn btn-primary"><?php echo JText::_('COM_EVENTGALLERY_CART_BUTTON_ORDER')?></button>
		</div>

		<div style="clear:both"></div>

	</div>

<?php ELSE: ?>
	<style type="text/css">
		.button-add2cart {
			display: none !important;
		}
	</style>
<?php ENDIF; ?>