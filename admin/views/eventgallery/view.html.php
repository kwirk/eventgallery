<?php


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
