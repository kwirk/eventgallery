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

<?php echo JText::_('COM_EVENTGALLERY_PASSWORD_ENTER_PASSWORD')?>



<div>
<?php echo JText::_('COM_EVENTGALLERY_PASSWORD_FORM_ERROR') ?>
<?php echo JText::_('COM_EVENTGALLERY_PASSWORD_FORM_PASSWORD_LABEL') ?>
<?php echo JText::_('COM_EVENTGALLERY_PASSWORD_FORM_PASSWORD_DESC') ?>


<form action="<?php echo $this->formaction;?>" method="POST">
	<input type="password" name="password">
	<input type="submit" name="submit" value="<?php echo JText::_('COM_EVENTGALLERY_PASSWORD_FORM_SUBMIT') ?>">
</form>
</div>