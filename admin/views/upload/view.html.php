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
    protected $item;

	function display($tpl = null)
	{

		$this->item		= $this->get('Item');

		JToolBarHelper::title(   JText::_( 'Event' ).': <small><small>[ upload ]</small></small>' );
		JToolBarHelper::cancel( 'cancelEvent', 'Close' );

		parent::display($tpl);
	}
}
?>
