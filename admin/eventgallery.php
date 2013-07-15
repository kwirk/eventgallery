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

if (!JFactory::getUser()->authorise('core.manage', 'com_eventgallery'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//load tables
JTable::addIncludePath(
    JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'tables'
);

JLoader::registerPrefix('Eventgallery', JPATH_COMPONENT_SITE);

// Execute the task.
$controller	= JControllerLegacy::getInstance('Eventgallery');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

