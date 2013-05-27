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


class EventgalleryViewEvents extends JViewLegacy
{

	function display($tpl = null)
	{	
		$cache = JFactory::getCache('com_eventgallery');
		
		$app = JFactory::getApplication();		
		
		$params	 = $app->getParams();
        $this->assign('params', $params);

		/* Default Page fallback*/		
		$active	= $app->getMenu()->getActive();
		if (null == $active) {
			$params = $app->getMenu()->getDefault()->params;
		}

		$entriesPerPage = 10;
		$model = $this->getModel('events');
		$eventModel = $this->getModel('event');

	    //$entries = $model->getEntries(JRequest::getVar('page',1),$entriesPerPage,$params->get('tags'));
		$entries = $cache->call( array( $model, 'getEntries' ), JRequest::getVar('page',1), $entriesPerPage, $params->get('tags'), $params->get('sort_events_by'));

		$fileCount = $model->getFileCount();
		$folderCount = $model->getFolderCount();
	    $this->assignRef('entries',	$entries );	    
	    $this->assignRef('fileCount', $fileCount);
	    $this->assignRef('folderCount', $folderCount);
	    $this->assignRef('eventModel', $eventModel);
    
        
		parent::display($tpl);
	}
}

?>
