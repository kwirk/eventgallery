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
	    $cache =  JFactory::getCache();
		$app	 = JFactory::getApplication();
		$document = JFactory::getDocument();
		$params	 = $app->getParams();
		
		JHtml::_('behavior.framework', true);
		
	    $css=JURI::base().'components/com_eventgallery/media/css/eventgallery.css';
		$document->addStyleSheet($css);		
		$css=JURI::base().'components/com_eventgallery/media/css/mediaboxAdvBlack21.css';
		$document->addStyleSheet($css);		
	    $js=JURI::base().'components/com_eventgallery/media/js/eventgallery.js';
		$document->addScript($js);
		$js=JURI::base().'components/com_eventgallery/media/js/mediaboxAdv-1.3.4b.js';
		$document->addScript($js);
	
			
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
	    	$app->redirect(JRoute::_("index.php?"));
	    }
	    
	    $this->assignRef('pageNav', $pageNav);
	    $this->assignRef('entries',	$entries );
	    $entryCount  = count($entries);
	    $this->assignRef('entriesCount', $entryCount);
	    $this->assignRef('folder',	$folder );
	    
	    $pathway = JSite::getPathWay();
		$pathway->addItem($folder->description);
		
	    
		
		$this->assign('params', $params);
        $this->assign('use_comments', $params->get('use_comments'));
		
		if ($this->getLayout() =='ajaxpaging') {
			$js=JURI::base().'components/com_eventgallery/media/js/JSGallery2.js';
			$document->addScript($js);
		}
		elseif ($this->getLayout() =='imagelist') {
			$js=JURI::base().'components/com_eventgallery/media/js/LazyLoad.js';
			$document->addScript($js);
			$this->assign('image_array', $params->get('image_array'));
		} else {
			$js=JURI::base().'components/com_eventgallery/media/js/LazyLoad.js';
			$document->addScript($js);
		}
		
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
		$pathway = $app->getPathway();
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

		if ($this->folder->text)
		{
			$this->document->setDescription($this->folder->text);
		}
		elseif (!$this->folder->text && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
	}
}

?>
