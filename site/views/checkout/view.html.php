<?php


jimport( 'joomla.application.component.view');


class EventgalleryViewCheckout extends JViewLegacy
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
	    
	    /*
		$model = & $this->getModel('singleimage');		
		$model->getData(JRequest::getString('folder'),JRequest::getString('file'));
		$this->assign('model',$model);
		*/

		$params	 = &$app->getParams();
		$this->assign('params', $params);
		
		$pathway =& JSite::getPathWay();		
		$pathway->addItem(JText::_('COM_EVENTGALLERY_CART_CHECKOUT_PATH'));
		
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

		$title .= " - ".JText::_('COM_EVENTGALLERY_CART_CHECKOUT_PATH');


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
		
		
		if ($this->document) {
				
			$this->document->setTitle($title);
			
		}
	}

}
?>