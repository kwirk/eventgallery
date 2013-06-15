<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 
$methodes = EventgalleryLibraryManagerShipping::getInstance()->getMethodes(true);
$currentMethod = $this->cart->getShipping()==null?EventgalleryLibraryManagerShipping::getInstance()->getDefaultMethode():$this->cart->getShipping();


?>

<div class="control-group">
	<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SHIPPINGMETHODE_LABEL') ?>
	<div class="controls">  
				
		
		<select class="" name="shippingid">
	        <?php FOREACH($methodes as $method): ?>
	        	<?php 

	        		$selected = "";
	        		
	        		if ($method->getId() == $currentMethod->getId()) {
	        			$selected = 'selected = "selected"';
	        		}
	        		
	        		
	        	?>
	            <option <?php echo $selected; ?> value="<?php echo $method->getId();?>"><?php echo $method->getDisplayName();?> (<?php echo $method->getCurrency();?> <?php echo $method->getPrice();?>) </option>
	        <?php ENDFOREACH ?>
	    </select>
	</div>
</div>

