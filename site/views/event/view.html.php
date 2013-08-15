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
jimport( 'joomla.application.pathway');
jimport( 'joomla.html.pagination');


class EventgalleryViewEvent extends JViewLegacy
{
	function display($tpl = null)
	{		
	    $cache =  JFactory::getCache('com_eventgallery');
		$app	 = JFactory::getApplication();
		$document = JFactory::getDocument();
		$params	 = $app->getParams();

		/* Default Page fallback*/		
		$active	= $app->getMenu()->getActive();
		if (null == $active) {
			$params->merge($app->getMenu()->getDefault()->params);
		}
	
			
		if ($layout = $params->get('event_layout'))
		{
			$this->setLayout($layout);
		}

		$model =  $this->getModel('event');		


		$pageNav = $model->getPagination(JRequest::getVar('folder',''));	
		
		$entries = "";
		if ($this->getLayout() =='ajaxpaging' || $this->getLayout() =='imagelist') {
	    	//$entries = $model->getEntries(JRequest::getVar('folder',''),-1,-1);
	    	$entries = $cache->call( array( $model, 'getEntries' ), JRequest::getVar('folder',''),-1,-1);
	    } else {
	        //$entries = $model->getEntries(JRequest::getVar('folder',''));
	        $entries = $cache->call( array( $model, 'getEntries' ), JRequest::getVar('folder',''));
	    }
	    
	    //$folder = $model->getFolder(JRequest::getVar('folder',''));
	    $folder = $cache->call( array( $model, 'getFolder' ), JRequest::getVar('folder',''));
	    
	    if (!is_object($folder)) {
	    	$app->redirect(JRoute::_("index.php?option=com_eventgallery", false), JText::_('COM_EVENTGALLERY_EVENT_NO_PUBLISHED_MESSAGE'), 'info');
	    }

	   	$password = JRequest::getString('password','');
		$accessAllowed = EventgalleryHelpersFolderprotection::isAccessAllowed($folder, $password);
		
		if (!$accessAllowed) {
			$app->redirect(JRoute::_("index.php?option=com_eventgallery&view=password&folder=".$folder->folder, false));	
		}				
	    
	    $this->assignRef('pageNav', $pageNav);
	    $this->assignRef('entries',	$entries );
	    $entryCount  = count($entries);
	    $this->assignRef('entriesCount', $entryCount);
	    $this->assignRef('folder',	$folder );
	    
	    $pathway = $app->getPathway();
		$pathway->addItem($folder->description);
		
	    
		
		$this->assign('params', $params);
        $this->assign('use_comments', $params->get('use_comments'));
				
		
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
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		

		$title = $this->params->get('page_title', '');

		if ($this->folder->description) {
			$title = $this->folder->description;
		}


		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		if (empty($title)) {
			$title = $this->folder->description;
		}
		$this->document->setTitle($title);

        if (strlen($this->params->get('menu-meta_description'))>0)  
        {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        } 
        elseif (strlen($this->folder->text)>0) 
        {
            $this->document->setDescription(strip_tags($this->folder->text));
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
