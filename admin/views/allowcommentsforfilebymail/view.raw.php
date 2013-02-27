<?php

defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');


class EventgalleryViewAllowCommentsForFileByMail extends JViewLegacy
{
	function display($tpl = null)
	{		
		
		$model = $this->getModel();

		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				
					$model->setId($cid);
					$file      =& $model->getData();
					
			}
		}

		

		$this->assignRef('file',		$file);

		parent::display();
	}
}
?>
