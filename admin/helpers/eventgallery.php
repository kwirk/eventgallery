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
        $currentLayout = JRequest::getString('layout','default');

        if ($vName == 'documentation') {

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_INTRO'),
                'index.php?option=com_eventgallery&view=documentation',
                $currentLayout=='default'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_REQUIREMENTS'),
                'index.php?option=com_eventgallery&view=documentation&layout=requirements',
                $currentLayout=='requirements'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_VIDEOS'),
                'index.php?option=com_eventgallery&view=documentation&layout=videos',
                $currentLayout=='videos'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_INSTALL'),
                'index.php?option=com_eventgallery&view=documentation&layout=install',
                $currentLayout=='install'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_USAGE'),
                'index.php?option=com_eventgallery&view=documentation&layout=usage',
                $currentLayout=='usage'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_CHECKOUT'),
                'index.php?option=com_eventgallery&view=documentation&layout=checkout',
                $currentLayout=='checkout'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_EXTEND'),
                'index.php?option=com_eventgallery&view=documentation&layout=extend',
                $currentLayout=='extend'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_FAQ'),
                'index.php?option=com_eventgallery&view=documentation&layout=faq',
                $currentLayout=='faq'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_EVENTGALLERY_SUBMENU_DOCUMENTATION_RELEASES'),
                'index.php?option=com_eventgallery&view=documentation&layout=releases',
                $currentLayout=='releases'
            );

            return;
        }

        JHtmlSidebar::addEntry(
            JText::_('COM_EVENTGALLERY_SUBMENU_EVENTGALLERY'),
            'index.php?option=com_eventgallery',
            $vName == 'eventgallery'
        );

		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS'),
			'index.php?option=com_eventgallery&view=events',
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
            JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPES'),
            'index.php?option=com_eventgallery&view=imagetypes',
            $vName == 'imagetypes' || $vName == 'imagetype');       

        JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_IMAGETYPESETS'),
			'index.php?option=com_eventgallery&view=imagetypesets',
			$vName == 'imagetypesets' || $vName == 'imagetypeset');

 		JHtmlSidebar::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_ORDERSTATUSES'),
			'index.php?option=com_eventgallery&view=orderstatuses',
			$vName == 'orderstatuses' || $vName == 'orderstatuse');

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