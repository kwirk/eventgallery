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
$version =  new JVersion();
if ($version->isCompatible('3.0')) {

} else {
    $css=JURI::base().'components/com_eventgallery/media/css/legacy.css';
    $document->addStyleSheet($css);
}
?>

<style type="text/css">
	.eventgallery-row {
		margin-bottom: 20px;
	}
</style>

<div class="container">

	<div class="row-fluid eventgallery-row">
		<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS_DESC')?></p>
			<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=events')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS')?></a>
		</div>
		<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_COMMENTS')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_COMMENTS_DESC')?></p>
			<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=comments')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_COMMENTS')?></a>
		</div>
		<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS_DESC')?></p>
			<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=orders')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS')?></a>
		</div>
	</div>

	<div class="row-fluid eventgallery-row">
		<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_DESC')?></p>
			<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=documentation')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION')?></a>
		</div>
		<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES_DESC')?></p>
			<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=imagetypes')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES')?></a>
		</div>
		<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS_DESC')?></p>
			<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=imagetypesets')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS')?></a>
		</div>	
	</div>

	<div class="row-fluid eventgallery-row">
		<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES_DESC')?></p>
			<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=surcharges')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES')?></a>
		</div>
			<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS_DESC')?></p>
			<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=shippingmethods')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS')?></a>
		</div>
			<div class="span4">
			<h2><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS')?></h2>
			<p><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS_DESC')?></p>
			<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=paymentmethods')?>"><?php echo JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS')?></a>
		</div>
	</div>



	<div class="well">
		<p>
			If you want to support the development of this component you might want to have a look at my PayPal donation page or at Flattr.
		</p>

		<p>
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=svenbluege%40gmail%2ecom&lc=GB&item_name=Event%20Gallery%20Development&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest"><img src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif"></a>
		

			<script type="text/javascript">
			/* <![CDATA[ */
			    (function() {
			        var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
			        s.type = 'text/javascript';
			        s.async = true;
			        s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
			        t.parentNode.insertBefore(s, t);
			    })();
			/* ]]> */</script>
			<a class="FlattrButton" style="display:none;" href="http://www.svenbluege.de/joomla-event-gallery"></a>
		</p>
		<p>
			For getting support or the lastest version of this component just visit my site: <a href="http://www.svenbluege.de">www.svenbluege.de</a>.
		</p>

	</div>

</div>

<form action="index.php" method="post" id="adminForm" name="adminForm">
	<input type="hidden" name="option" value="com_eventgallery" />
	<input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
