<?php


jimport( 'joomla.application.component.view');


class EventgalleryViewCommentMail extends JViewLegacy
{
	function display($tpl = null)
	{		
	    $this->_loadData();
		parent::display($tpl);		
	}
	
	function loadTemplate($tpl = null)
	{
	    $this->_loadData();
	    return parent::loadTemplate($tpl);
	}
	
	function _loadData()
	{
	    
    	$model = $this->getModel();
    	$newComment = $model->getData(JRequest::getVar('newCommentId'));
    	$file = $model->getFile($newComment->id);
			

	    $this->assignRef('newComment',$newComment);
	    $this->assignRef('file',$file);
	}
}
?>
