<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;



jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');


class EventgalleryViewEvent extends JViewLegacy
{
	function display($tpl = null)
	{		


		$app	  = JFactory::getApplication();
		$document = JFactory::getDocument();	
		
		
	    $css=JURI::base().'components/com_eventgallery/media/css/eventgallery.css';
		$document->addStyleSheet($css);		

		$event		= $this->get('Data');
		$items      = $this->get('Files');
		$pageNav    = $this->get('Pagination');
		$isNew		= ($event->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_EVENTS' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save('saveEvent','Save Event');
		
		if ($isNew)  {			
			JToolBarHelper::cancel('cancelEvent', 'Close');
			
		} else {
			JToolBarHelper::apply('applyEvent','Apply Changes');
			JToolBarHelper::cancel( 'cancelEvent', 'Close' );
		}
		
		$ordering = true;
		$this->assignRef('ordering', $ordering);

		
		$this->assignRef('files',		$items);
		$this->assignRef('pageNav', $pageNav);
		$this->assignRef('isNew', 		$isNew);
		$this->assignRef('event',		$event);

		parent::display($tpl);
	}
}
?>
