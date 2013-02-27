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

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination');


class EventgalleryViewComments extends JViewLegacy
{

	function display($tpl = null)
	{
		
		$app = JFactory::getApplication();
		
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_COMMENTS' ), 'generic.png' );
		JToolBarHelper::deleteList('Remove all comments?','removeComment','Remove');
		JToolBarHelper::editList('editComment','Edit');
		//JToolBarHelper::addNewX('editComment','New');
		JToolBarHelper::publishList('Commentpublish');
		JToolBarHelper::unpublishList('Commentunpublish');

		
		$model = $this->getModel();		
		
		
		$filter = $app->getUserStateFromRequest('com_eventgallery.comments.filter','filter');       
		$model->setState('com_eventgallery.comments.filter',$filter);
				   
		$pageNav = $model->getPagination();		
		$items = $model->getItems();
		
		$this->assignRef('items',		$items);
		$this->assignRef('pageNav', $pageNav);
		$this->assignRef('filter', $filter);

		parent::display($tpl);
	}

}
