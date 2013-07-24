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


class EventgalleryViewSingleImage extends JViewLegacy
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
			$params = $app->getMenu()->getDefault()->params;
		}

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
		

		$this->setLayout(JRequest::getString('layout','minipage'));

		parent::display($tpl);
	}	

}
?>