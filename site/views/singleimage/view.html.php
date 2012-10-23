<?php


jimport( 'joomla.application.component.view');


class EventgalleryViewSingleImage extends JViewLegacy
{
	function display($tpl = null)
	{		
	    JHtmlBehavior::framework();
	    $app	 = &JFactory::getApplication();
	    $document =& JFactory::getDocument();
	    
	    
	    
		
	    $css=JURI::base().'components/com_eventgallery/media/css/eventgallery.css';
		$document->addStyleSheet($css);		
		$css=JURI::base().'components/com_eventgallery/media/css/mediaboxAdvBlack21.css';
		$document->addStyleSheet($css);		
	    $js=JURI::base().'components/com_eventgallery/media/js/eventgallery.js';
		$document->addScript($js);
		$js=JURI::base().'components/com_eventgallery/media/js/mediaboxAdv-1.3.4b.js';
		$document->addScript($js);
		$js=JURI::base().'components/com_eventgallery/media/js/LazyLoad.js';
		$document->addScript($js);	
	    
	    
		$model = & $this->getModel('singleimage');		
		$model->getData(JRequest::getString('folder'),JRequest::getString('file'));
		$this->assign('model',$model);
		
		$params	 = &$app->getParams();
        $this->assign('use_comments', $params->get('use_comments'));
        $this->assign('paging_images_count', $params->get('paging_images_count'));
        $this->assign('singleimage_preview', $params->get('singleimage_preview'));
        $this->assign('page_width',$params->get('page_width', 600));
		$this->assign('params', $params);
		
		$folder = $model->folder;
		
		if (!is_object($folder)) {
			$app->redirect(JRoute::_("index.php?"));
		}
		
		
		if (!isset($model->file) || strlen($model->file->file)==0) {
			$app->redirect(JRoute::_("index.php?view=event&folder=".$folder->folder));
		}
		
		$pathway =& JSite::getPathWay();
		$pathway->addItem($folder->description,JRoute::_('index.php?view=event&folder='.$folder->folder,false));
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

			if ($this->model->folder->text)
			{
				$this->document->setDescription($this->model->folder->text);
			}
			elseif (!$this->model->folder->text && $this->params->get('menu-meta_description'))
			{
				$this->document->setDescription($this->params->get('menu-meta_description'));
			}
		}
	}

}
?>