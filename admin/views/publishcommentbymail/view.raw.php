<?php


jimport( 'joomla.application.component.view');
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');


class EventgalleryViewPublishCommentByMail extends JViewLegacy
{
	function display($tpl = null)
	{		
		
		$model = $this->getModel();

		$cids = JRequest::getVar( 'cid', array(0), '', 'array' );
		if (count( $cids ))
		{
			foreach($cids as $cid) {
				
					$model->setId($cid);
					$comment      =& $model->getData();
					
			}
		}

		

		$this->assignRef('comment',		$comment);

		parent::display();
	}
}
?>
