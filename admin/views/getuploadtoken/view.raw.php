<?php


jimport( 'joomla.application.component.view');

class EventgalleryViewGetUploadToken extends JViewLegacy
{
	function display($tpl = null)
	{		
		$model = $this->getModel();
		$token = $model->generateToken(JRequest::getVar('folder'));
		$this->assignRef('token',	$token);
		parent::display();
	}
}
?>