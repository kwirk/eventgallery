<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 

$document = JFactory::getDocument();    
$css=JURI::base().'components/com_eventgallery/media/css/eventgallery.css';
$document->addStyleSheet($css);     

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'orderstatus.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            
            Joomla.submitform(task, document.getElementById('adminForm'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_eventgallery&layout=edit&id='.(int) $this->item->id); ?>" method="POST" name="adminForm" id="adminForm">

<div class="col100">
	<fieldset class="adminform">


            <div class="control-group"><?php echo $this->form->getLabel('name'); ?>
                <div class="controls">
                    <?php echo $this->form->getInput('name'); ?>
                </div>
            </div>
            <?php IF ($this->item->id == 0): ?>
                <div class="control-group"><?php echo $this->form->getLabel('type'); ?>
                    <div class="controls">
                        <?php echo $this->form->getInput('type'); ?>
                    </div>
                </div>
            <?php ENDIF ?>
            <div class="control-group"><?php echo $this->form->getLabel('displayname'); ?>
                <div class="controls">
                    <?php echo $this->form->getInput('displayname'); ?>
                </div>
            </div>
            <div class="control-group"><?php echo $this->form->getLabel('description'); ?>
                <div class="controls">
                    <?php echo $this->form->getInput('description'); ?>
                </div>
            </div>

        <?php echo $this->form->getInput('id'); ?>

    </fieldset>
</div>

<div class="clr"></div>

<?php echo JHtml::_('form.token'); ?>
<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
<input type="hidden" name="task" value="" />
</form>