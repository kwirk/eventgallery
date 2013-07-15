<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');


/** @noinspection PhpUndefinedClassInspection */
class EventgalleryViewEvents extends JViewLegacy
{
	function display($tpl = null)
	{		
		
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_EVENTS' ), 'generic.png' );
		//JToolBarHelper::deleteList();
		JToolBarHelper::addNew('event.add');
		JToolBarHelper::editList('event.edit');
		JToolBarHelper::publishList('events.publish');
		JToolBarHelper::unpublishList('events.unpublish');
        JToolBarHelper::publishList('events.cartable','COM_EVENTGALLERY_EVENT_CARTABLE');
        JToolBarHelper::unpublishList('events.notcartable','COM_EVENTGALLERY_EVENT_NOT_CARTABLE');
		JToolBarHelper::deleteList('Remove all selected Events?','events.delete','Remove');
		JToolBarHelper::preferences('com_eventgallery', '550');

		JToolBarHelper::spacer(100);

		$bar = JToolbar::getInstance('toolbar');

		// Add a trash button.
				
		$bar->appendButton('Confirm', 'COM_EVENTGALLERY_CLEAR_CACHE_ALERT', 'trash', 'COM_EVENTGALLERY_SUBMENU_CLEAR_CACHE',  'clearCache', false);
		$bar->appendButton('Confirm', 'COM_EVENTGALLERY_SYNC_DATABASE_SYNC_ALERT', 'checkin', 'COM_EVENTGALLERY_SUBMENU_SYNC_DATABASE',  'refreshDatabase', false);
		
		// Get data from the model
		
		$model = $this->getModel();		
		$pagination = $model->getPagination();		
		$items = $model->getItems();
		
		$ordering = true;
		$this->assignRef('ordering', $ordering);

		$this->assignRef('items',		$items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}

