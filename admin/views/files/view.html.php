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


class EventgalleryViewFiles extends JViewLegacy
{
	function display($tpl = null)
	{		


		$app	  = JFactory::getApplication();
		$document = JFactory::getDocument();	
				
	    $css=JURI::base().'components/com_eventgallery/media/css/eventgallery.css';
		$document->addStyleSheet($css);		

		$event		= $this->get('Data');

		#print_r($this);

		
		$items      = $this->get('Files');
		$pageNav    = $this->get('Pagination');
		$isNew		= ($event->id < 1);

		$text = $event->folder;
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_EVENTS' ).': <small><small>[ ' . $text.' ]</small></small>' );				

		JToolBarHelper::cancel('cancelEvent', 'Close');


		JToolBarHelper::custom('Filepublish', 'eg-published');
		JToolBarHelper::custom('Fileunpublish', 'eg-published-inactive');

		JToolBarHelper::custom('allowComments', 'eg-comments');
		JToolBarHelper::custom('disallowComments', 'eg-comments-inactive');


		JToolBarHelper::custom('isMainImage', 'eg-mainimage');
		JToolBarHelper::custom('isNotMainImage', 'eg-mainimage-inactive');

		JToolBarHelper::custom('isNotMainImageOnly', 'eg-mainimageonly');
		JToolBarHelper::custom('isMainImageOnly', 'eg-mainimageonly-inactive');

		JToolBarHelper::spacer(50);

		JToolBarHelper::deleteList(JText::_( 'COM_EVENTGALLERY_EVENT_IMAGE_ACTION_DELETE_ALERT' ), 'removeFile');		
		
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
