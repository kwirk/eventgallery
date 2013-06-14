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

<?php foreach($this->userdataform->getFieldset() as $field): ?>
	<div class="control-group">
	<?php if (!$field->hidden): ?>
   		<?php echo $field->label; ?>
	<?php endif; ?>
	<div class="controls">  
	<?php echo $field->input; ?>
	</div>
			</div>
<?php endforeach; ?>
<hr>

<?php foreach($this->billingform->getFieldset() as $field): ?>
	<div class="control-group">
	<?php if (!$field->hidden): ?>
   		<?php echo $field->label; ?>
	<?php endif; ?>
	<div class="controls">  
	<?php echo $field->input; ?>
	</div>
			</div>
<?php endforeach; ?>

<hr>
<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SHIPTODIFFERENTADDRESS') ?>
False
<input type="radio" name="shiptodifferentaddress" value="false" <?php echo @$this->cart->getShippingAddress()->getId()==@$this->cart->getBillingAddress()->getId()?'checked=checked':''?>>
True
<input type="radio" name="shiptodifferentaddress" value="true" <?php echo @$this->cart->getShippingAddress()->getId()!=@$this->cart->getBillingAddress()->getId()?'checked=checked':''?>>
<hr>

<?php foreach($this->shippingform->getFieldset() as $field): ?>
	<div class="control-group">
	<?php if (!$field->hidden): ?>
   		<?php echo $field->label; ?>
	<?php endif; ?>
	<div class="controls">  
	<?php echo $field->input; ?>
	</div>
			</div>
<?php endforeach; ?>


<div class="eventgallery-checkout-address">

	    <fieldset>	  

			<div class="control-group">
				<label class="control-label" for="message"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_MESSAGE')?></label>
				<div class="controls">            
					<textarea name="message" class=" input-xlarge" rows="8" placeholder="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_MESSAGE_PLACEHOLDER')?>"><?php echo $this->cart->getMessage(); ?></textarea>
				</div>
			</div>

	    </fieldset>
	
	

</div>



