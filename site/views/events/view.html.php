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
    
        $this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}

?>
