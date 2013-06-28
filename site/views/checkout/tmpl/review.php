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
    <h1><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_HEADLINE') ?></h1>
    <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_TEXT') ?>&nbsp;
    <!--<a class="" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=cart") ?>"><?php echo JText::_('COM_EVENTGALLERY_CART')?> <i class="icon-arrow-right"></i></a>-->
    <form action="<?php echo JRoute::_("index.php?option=com_eventgallery&view=checkout&task=createOrder") ?>"
          method="post" class="form-validate form-horizontal checkout-form">

        <?php $this->set('edit',true); $this->set('lineitemcontainer',$this->cart); echo $this->loadSnippet('checkout/summary') ?>


        <div class="clearfix"></div>

        <?php IF ($this->params->get('use_terms_conditions_checkbox', 1)==1):?>
        <fieldset>
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox">                  
                        <input type="checkbox" name="tac" class="validate required">    
                        <?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_TERMCONDITIONS_CHECKBOX_LABEL') ?>       
                    </label>
                </div>
            </div>
        </fieldset>
        <?php ENDIF; ?>

        <fieldset>
            <div class="form-actions">
                  <a class="btn" href="<?php echo JRoute::_(
                        "index.php?option=com_eventgallery&view=checkout&task=change"
                    ) ?>"><?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CHANGE') ?></a>
                
                <input name="continue" type="submit" class="btn btn-primary"
                       value="<?php echo JText::_('COM_EVENTGALLERY_CART_CHECKOUT_REVIEW_FORM_CONTINUE') ?>"/>
            </div>
        </fieldset>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>



