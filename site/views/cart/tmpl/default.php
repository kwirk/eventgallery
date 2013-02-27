<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
?>

<?php include 'components/com_eventgallery/views/cart.php'; ?>

<div class="eventgallery-cart-empty">
	<h2><?php echo JText::_('COM_EVENTGALLERY_CART')?></h2>		
	<?php echo JText::_('COM_EVENTGALLERY_CART_EMPTY')?>
</div>