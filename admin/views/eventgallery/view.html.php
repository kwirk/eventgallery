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

/** @noinspection PhpUndefinedClassInspection */
class EventgalleryViewEventgallery extends EventgalleryLibraryCommonView
{
    protected $version;

	function display($tpl = null)
	{				
		// Get data from the model
        $componentId = JComponentHelper::getComponent('com_eventgallery')->id;
        $extension = JTable::getInstance('extension');
        $extension->load($componentId);
        $data = json_decode($extension->manifest_cache, true);
        $this->version = $data['version'];

		EventgalleryHelpersEventgallery::addSubmenu('events');		
		$this->sidebar = JHtmlSidebar::render();
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_EVENTGALLERY' ) . " ". $this->version, 'generic.png' );
		//JToolBarHelper::deleteList();
		JToolBarHelper::preferences('com_eventgallery', '550');

		JToolBarHelper::spacer(100);

		$bar = JToolbar::getInstance('toolbar');

		// Add a trash button.
				
		$bar->appendButton('Confirm', 'COM_EVENTGALLERY_CLEAR_CACHE_ALERT', 'trash', 'COM_EVENTGALLERY_SUBMENU_CLEAR_CACHE',  'clearCache', false);
		$bar->appendButton('Confirm', 'COM_EVENTGALLERY_SYNC_DATABASE_SYNC_ALERT', 'checkin', 'COM_EVENTGALLERY_SUBMENU_SYNC_DATABASE',  'refreshDatabase', false);
		
	}
}

