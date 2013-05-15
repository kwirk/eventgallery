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

class EventgalleryViewComment extends JViewLegacy
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the hello
		$comment		= $this->get('Data');
		$isNew		= ($comment->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_COMMENTS' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save('saveComment','Save');
		if ($isNew)  {
			JToolBarHelper::cancel('cancelComment', 'Close');
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancelComment', 'Close' );
		}
		$this->assignRef('comment',		$comment);

		parent::display($tpl);
	}
}
