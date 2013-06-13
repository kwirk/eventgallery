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
    protected $params;
    protected $entries;
    protected $fileCount;
    protected $folderCount;
    protected $eventModel;

	function display($tpl = null)
	{
        /**
         * @var JCacheControllerCallback $cache
         */
        $cache = JFactory::getCache('com_eventgallery');

        /**
         * @var JSite $app
         */
        $app = JFactory::getApplication();
		
        $this->params = $app->getParams();

		/* Default Page fallback*/		
		$active	= $app->getMenu()->getActive();
		if (null == $active) {
           $this->params->merge($app->getMenu()->getDefault()->params);
		}

		$entriesPerPage = 10;
		$model = $this->getModel('events');
		$eventModel = $this->getModel('event');

	    //$entries = $model->getEntries(JRequest::getVar('page',1),$entriesPerPage,$params->get('tags'));
		$entries = $cache->call( array( $model, 'getEntries' ), JRequest::getVar('page',1), $entriesPerPage, $this->params->get('tags'), $this->params->get('sort_events_by'));


	    $this->entries = $entries;
	    $this->fileCount = $model->getFileCount();
	    $this->folderCount = $model->getFolderCount();
	    $this->eventModel = $eventModel;
    
        
		parent::display($tpl);
	}
}

