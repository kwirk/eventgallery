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
<h1><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_ITEMS_IN_YOUR_CART')?></h1>
<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_TEXT')?>&nbsp;
<a class="" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=cart") ?>"><?php echo JText::_('COM_EVENTGALLERY_CART')?> <i class="icon-arrow-right"></i></a>
	<form action="<?php echo JRoute::_("index.php?option=com_eventgallery&view=checkout&task=sendOrder") ?>" method="post" class="form-validate form-horizontal checkout-form">
		<div class="cart-items">
			<div class="control-group">
				<label class="control-label" for="images"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_ITEMS')?></label>
				<div class="controls">
					<?php foreach($this->cart as $lineitem) :?>
						<div class="cart-item">
							<?php echo $lineitem['imagetag'] ?><br />
							<input class="validate-numeric required input-small" type="number" name="count_<?php echo md5($lineitem['folder'].$lineitem['file']) ?>" value="<?php echo $lineitem['count'] ?>"/>			
						</div>
					<?php endforeach?>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>		
	    <fieldset>	    		

	        <div class="control-group">
	           	<label id="name-lbl" class="control-label" for="name"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_NAME')?></label>
		        <div class="controls">
		            <input type="text" name="name" class="required input-xlarge" id="name" placeholder="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_NAME_PLACEHOLDER')?>">
		        </div>
	        </div>
			<div class="control-group">
				<label id="email-lbl" class="control-label" for="email"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_EMAIL')?></label>
				<div class="controls">
					<input type="email" name="email" class="required validate-email input-xlarge" id="email" placeholder="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_EMAIL_PLACEHOLDER')?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="subject">Select Service</label>
				<div class="controls">
					<select name="subject" id="subject">
						<option value="digital"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SUBJECT_DIGITAL')?></option>
						<option selected="seclected" value="paper"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SUBJECT_PAPER')?></option>
						<option value="other"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SUBJECT_OTHER')?></option>                
					</select>
				</div>
			</div>         
			<div class="control-group">
				<label id="message-lbl" class="control-label" for="message"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_MESSAGE')?></label>
				<div class="controls">            
					<textarea name="message" id="message" class="required input-xlarge" rows="8" placeholder="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_MESSAGE_PLACEHOLDER')?>"></textarea>
				</div>
			</div>
			<div class="form-actions">
				<input type="submit" class="validate btn btn-primary" value="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_FORM_SUBMIT')?>"/>           
			</div>
	    </fieldset>
	    <?php echo JHtml::_('form.token'); ?>
	</form>
</div>
