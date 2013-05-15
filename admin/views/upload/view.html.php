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


class EventgalleryViewUpload extends JViewLegacy
{
	function display($tpl = null)
	{	
		$event		= $this->get('Data');
		$files      = $this->get('Files');
		
		JToolBarHelper::title(   JText::_( 'Event' ).': <small><small>[ upload ]</small></small>' );

		JToolBarHelper::cancel( 'cancelEvent', 'Close' );

		$this->assignRef('event',		$event);
		$this->assignRef('files',		$files);
		
		parent::display($tpl);
	}
}
?>
