<?php 
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');


class EventgalleryViewEvents extends JViewLegacy
{
	function display($tpl = null)
	{	
		$cache = & JFactory::getCache();
		
		$app	 = &JFactory::getApplication();
		$document =& JFactory::getDocument();	
		
		JHtml::_('behavior.framework');
		
	    $css=JURI::base().'components/com_eventgallery/media/css/eventgallery.css';
		$document->addStyleSheet($css);		
		$css=JURI::base().'components/com_eventgallery/media/css/mediaboxAdvBlack21.css';
		$document->addStyleSheet($css);		
	    $js=JURI::base().'components/com_eventgallery/media/js/eventgallery.js';
		$document->addScript($js);
		$js=JURI::base().'components/com_eventgallery/media/js/mediaboxAdv-1.3.4b.js';
		$document->addScript($js);
		
		
		$params	 = &$app->getParams();
        $this->assign('params', $params);
		
		$entriesPerPage = 10;
		$model = & $this->getModel('events');
		$eventModel = & $this->getModel('event');

	    //$entries = $model->getEntries(JRequest::getVar('page',1),$entriesPerPage,$params->get('tags'));
		$entries = $cache->call( array( $model, 'getEntries' ), JRequest::getVar('page',1),$entriesPerPage,$params->get('tags'));


		
	    $this->assignRef('entries',	$entries );	    
	    $this->assignRef('fileCount',$model->getFileCount());
	    $this->assignRef('folderCount',$model->getFolderCount());
	    $this->assignRef('eventModel',$eventModel);
	    
	    
        
		parent::display($tpl);
	}
}

?>
