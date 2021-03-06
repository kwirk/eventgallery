<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controllerform' );

class EventgalleryControllerMethod extends JControllerForm
{

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   EventgalleryModelMethod  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function postSaveHook($model, $validData = array())
	{
        if ($validData['default']==1) {
            $ids = array($validData['id']);
            $model->setDefault($ids, 1);
        }
	}
}
