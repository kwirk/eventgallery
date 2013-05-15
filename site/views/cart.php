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
	$myApp	 = JFactory::getApplication();
	$myParams	 = $myApp->getParams();
	$use_cart = !(strcmp('0',$myParams->get('use_cart','1'))==0);
?>

<?php IF($use_cart): ?>

	<script type="text/javascript">	
		/* <![CDATA[ */
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
				'removeUrl' :  "<?php echo JRoute::_("index.php?option=com_eventgallery&view=rest&task=removefromcart&format=raw", true); ?>".replace(/&amp;/g, '&'),
				'add2cartUrl' : "<?php echo JRoute::_("index.php?option=com_eventgallery&view=rest&task=add2cart&format=raw", true); ?>".replace(/&amp;/g, '&'),
				'removeLinkTitle' : "<?php echo JText::_('COM_EVENTGALLERY_CART_ITEM_REMOVE')?>",
				'getCartUrl' : "<?php echo JRoute::_("index.php?option=com_eventgallery&view=rest&task=getCart&format=raw", true); ?>".replace(/&amp;/g, '&')
			};

			var eventgalleryCart = new EventgalleryCart(options);
			
		});
		/* ]]> */
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
			<button onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_eventgallery&view=checkout");?>'" class="btn btn-primary"><?php echo JText::_('COM_EVENTGALLERY_CART_BUTTON_ORDER')?></button>
			<button class="btn" data-rel="lightbo2" data-href="#mb_cart-help">?</button>
		</div>
		<div  style="display:none">
			<div id="mb_cart-help">
				<h2><?php echo JText::_('COM_EVENTGALLERY_CART_HELP_HEADLINE')?></h2>
				<?php echo JText::_('COM_EVENTGALLERY_CART_HELP_TEXT')?>				
			</div>
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