<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;


class EventgalleryHelpersEventgallery
{
	
	public static function addSubmenu($vName = 'events')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS'),
			'index.php?option=com_eventgallery',
			$vName == 'events' || $vName=='event' || $vName=='files'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_COMMENTS'),
			'index.php?option=com_eventgallery&view=comments',
			$vName == 'comments' || $vName == 'comment');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS'),
			'index.php?option=com_eventgallery&view=orders',
			$vName == 'orders' || $vName == 'order');

        JHtmlSidebar::addEntry(
            '<hr>',
            '#',
            false);

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_ORDERSTATUSES'),
			'index.php?option=com_eventgallery&view=orderstatuses',
			$vName == 'orderstatuses' || $vName == 'orderstatuse');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS'),
			'index.php?option=com_eventgallery&view=imagetypesets',
			$vName == 'imagetypesets' || $vName == 'imagetypeset');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES'),
			'index.php?option=com_eventgallery&view=imagetypes',
			$vName == 'imagetypes' || $vName == 'imagetype');
		
		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_SURCHARGES'),
			'index.php?option=com_eventgallery&view=surcharges',
			$vName == 'surcharges' || $vName == 'surcharge');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_SHIPPINGMETHODS'),
			'index.php?option=com_eventgallery&view=shippingmethods',
			$vName == 'shippingmethods' || $vName == 'shippingmethod');

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_PAYMENTMETHODS'),
			'index.php?option=com_eventgallery&view=paymentmethods',
			$vName == 'paymentmethods' || $vName == 'paymentmethod');

        JHtmlSidebar::addEntry(
            '<hr>',
            '#',
            false);

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION'),
			'index.php?option=com_eventgallery&view=documentation',
			$vName == 'documentation'
		);
	}
	
}