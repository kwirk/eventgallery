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


class EventgalleryViewSingleimage extends JViewLegacy
{
	function display($tpl = null)
	{		

	    $app	 = JFactory::getApplication();	   
	    
	    
		$model = $this->getModel('singleimage');		
		$model->getData(JRequest::getString('folder'),JRequest::getString('file'));
		$this->assign('model',$model);
		
		$params	 = $app->getParams();

		/* Default Page fallback*/		
		$active	= $app->getMenu()->getActive();
		if (null == $active) {
			$params->merge($app->getMenu()->getDefault()->params);
		}

        $this->assign('use_comments', $params->get('use_comments'));
        $this->assign('paging_images_count', $params->get('paging_images_count'));
        $this->assign('singleimage_preview', $params->get('singleimage_preview'));
        $this->assign('page_width',$params->get('page_width', 600));
		$this->assign('params', $params);
		
		$folder = $model->folder;
		
		if (!is_object($folder)) {
			$app->redirect(JRoute::_("index.php?option=com_eventgallery", false));
		}
		
		
		if (!isset($model->file) || strlen($model->file->file)==0) {
			$app->redirect(JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$folder->folder, false), JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NO_PUBLISHED_MESSAGE'), 'info');
		}

		$password = JRequest::getString('password','');
		$accessAllowed = EventgalleryHelpersFolderprotection::isAccessAllowed($folder, $password);
		if (!$accessAllowed) {
			$app->redirect(JRoute::_("index.php?option=com_eventgallery&view=password&folder=".$folder->folder, false));	
		}
		
		$pathway = $app->getPathWay();
		$pathway->addItem($folder->description,JRoute::_('index.php?option=com_eventgallery&view=event&folder='.$folder->folder));
		$pathway->addItem($model->position.' / '.$model->overallcount);
		
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

		if ($this->model->folder->description) {
			$title = $this->model->folder->description;
		}
		
		$title .= " - ".$this->model->position.' / '.$this->model->overallcount;


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
			$title = $this->model->folder->description;
		}
		
		if ($this->document) {

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

}
?>