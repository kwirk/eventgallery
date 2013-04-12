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


class EventgalleryViewEventgallery extends JViewLegacy
{
	function display($tpl = null)
	{		
		
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_EVENTS' ), 'generic.png' );
		//JToolBarHelper::deleteList();
		JToolBarHelper::addNew('newFolder');
		JToolBarHelper::editList('editEvent');
		JToolBarHelper::publishList('publish');
		JToolBarHelper::unpublishList('unpublish');
		JToolBarHelper::deleteList('Remove all selected Events?','removeEvent','Remove');
		JToolBarHelper::preferences('com_eventgallery', '550');

		JToolBarHelper::spacer(100);

		$bar = JToolbar::getInstance('toolbar');

		// Add a trash button.
				
		$bar->appendButton('Confirm', 'COM_EVENTGALLERY_CLEAR_CACHE_ALERT', 'trash', 'COM_EVENTGALLERY_SUBMENU_CLEAR_CACHE',  'clearCache', false);
		$bar->appendButton('Confirm', 'COM_EVENTGALLERY_SYNC_DATABASE_SYNC_ALERT', 'checkin', 'COM_EVENTGALLERY_SUBMENU_SYNC_DATABASE',  'refreshDatabase', false);
		
		// Get data from the model
		
		$model = $this->getModel();		
		$pageNav = $model->getPagination();		
		$items = $model->getItems();
		
		$ordering = true;
		$this->assignRef('ordering', $ordering);

		$this->assignRef('items',		$items);
		$this->assignRef('pageNav', $pageNav);

		parent::display($tpl);
	}
}
?>
