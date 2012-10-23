<?php


jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');


class EventgalleryViewUpload extends JViewLegacy
{
	function display($tpl = null)
	{	
		$event		=& $this->get('Data');
		$files      =& $this->get('Files');
		
		JToolBarHelper::title(   JText::_( 'Event' ).': <small><small>[ upload ]</small></small>' );

		JToolBarHelper::back('Back', 'javascript:history.back()');

		$this->assignRef('event',		$event);
		$this->assignRef('files',		$files);
		
		parent::display($tpl);
	}
}
?>
