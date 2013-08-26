<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controlleradmin' );

class EventgalleryControllerComments extends JControllerAdmin
{

    protected $model_name = 'Comments';
    protected $default_view = 'comments';

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    /**
     * Proxy for getModel.
     */
    public function getModel($name = 'Comment', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

	/**
	 * method to call edit-Events-View
	 * 
	 * @return void
	 */
/*	function edit()
	{
		JRequest::setVar( 'view', 'comment' );
		JRequest::setVar('hidemainmenu', 1);
		$this->display();
	}
*/
	/**
	 * method to save a comment after editing it
	 *//*
	function save()
	{
		
		JRequest::setVar( 'view', 'comment' );
		$model = $this->getModel('comment');
        $post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_SAVED_SUCCESS' );
		} else {
			$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_SAVED_ERROR' );
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect( 'index.php?option=com_eventgallery&view=comments&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}*/



}