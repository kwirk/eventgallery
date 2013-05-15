<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class EventgalleryViewDocumentation extends JViewLegacy
{
	function display($tpl = null)
	{		
		$document = JFactory::getDocument();	
	
	    $css=JURI::base().'administrator/components/com_eventgallery/media/css/manual.css';
		$document->addStyleSheet($css);	

		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_SUBMENU_DOCUMENTATION' ) );				
		JToolBarHelper::cancel('cancelEvent', 'Close');


		parent::display($tpl);
	}
}
?>
