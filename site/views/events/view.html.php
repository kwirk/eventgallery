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

	protected $pageNav;
	protected $cache;
	protected $params;


	function display($tpl = null)
	{	
		$this->cache = JFactory::getCache('com_eventgallery');
		
		$app = JFactory::getApplication();		
		
		$params	 = $app->getParams();
        $this->params = $params;

		/* Default Page fallback*/		
		$active	= $app->getMenu()->getActive();
		if (null == $active) {
			$params->merge($app->getMenu()->getDefault()->params);
		}

		$entriesPerPage = $params->get('max_events_per_page', 12);
		$model = $this->getModel('events');
		$eventModel = $this->getModel('event');

	    //$entries = $model->getEntries(JRequest::getVar('page',1),$entriesPerPage,$params->get('tags'));
		$entries = $this->cache->call( array( $model, 'getEntries' ), JRequest::getVar('start',0), $entriesPerPage, $params->get('tags'), $params->get('sort_events_by'));

		$this->pageNav = $model->getPagination();

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
