<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Weblink controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 * @since       1.6
 */
class EventgalleryControllerEvent extends JControllerForm
{

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{

        $oldFolder = JRequest::getVar("oldfolder", null);
		$newFolder = $validData['folder'];
		
		# Rename folder now:
		$basedir=JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;

		if ($oldFolder!=null && strcmp($oldFolder, $newFolder)!=0)
		{
			rename($basedir.$oldFolder, $basedir.$newFolder);
			$model->changeFolderName($oldFolder, $newFolder);
		}

		if ($this->task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_eventgallery&view=events', false));
		}
	}
}
