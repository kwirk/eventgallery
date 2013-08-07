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


class EventgalleryViewComments extends EventgalleryLibraryCommonView
{

	protected $items;
	protected $paginations;
	protected $filer;

	function display($tpl = null)
	{
		
		$app = JFactory::getApplication();
		


		
		$model = $this->getModel();		
		
		
		$this->filter = $app->getUserStateFromRequest('com_eventgallery.comments.filter','filter');       
		$model->setState('com_eventgallery.comments.filter',$this->filter);
				   
		$this->pagination = $model->getPagination();		
		$this->items = $model->getItems();

        JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_COMMENTS' ), 'generic.png' );
        JToolBarHelper::deleteList('Remove all comments?','removeComment','Remove');
        JToolBarHelper::editList('editComment','Edit');
        //JToolBarHelper::addNewX('editComment','New');
        JToolBarHelper::publishList('Commentpublish');
        JToolBarHelper::unpublishList('Commentunpublish');

		EventgalleryHelpersEventgallery::addSubmenu('comments');		
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

}
