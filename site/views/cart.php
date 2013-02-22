<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">	
	window.addEvent("domready", function() {
		var options = {
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
	<div class="cart-items-container">
		<div class="cart-items"></div>
	</div>
	<div style="clear:both"></div>
	<a class="toggle-down" href="#"><?php echo JText::_('COM_EVENTGALLERY_CART_ITEMS_TOGGLE_DOWN')?></a>
	<a class="toggle-up" href="#"><?php echo JText::_('COM_EVENTGALLERY_CART_ITEMS_TOGGLE_UP')?></a>	
	
	<div class="cart-summary">
		<span class="itemscount">0</span> <?php echo JText::_('COM_EVENTGALLERY_CART_ITEMS')?>
		<a href="<?php echo JRoute::_("index.php?view=checkout");?>" class="btn btn-primary"><?php echo JText::_('COM_EVENTGALLERY_CART_BUTTON_ORDER')?></a>
	</div>
	
</div>