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

//load tables
JTable::addIncludePath(
    JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_eventgallery'
    . DIRECTORY_SEPARATOR . 'tables'
);

// load forms
JForm::addFormPath(JPATH_COMPONENT . '/models/forms');

//load fields
JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

//load classes
JLoader::registerPrefix('Eventgallery', JPATH_COMPONENT);

// Load necessary media files 
EventgalleryHelpersMedialoader::load();

// Require the base controller
require_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'controller.php');


// Require specific controller if requested
if ($controller = JFactory::getApplication()->input->get('controller')) {
    require_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controller . '.php');
}


$view = JFactory::getApplication()->input->get('view', 'null');
if (strcmp('rest', $view) == 0 || strcmp('cart', $view) == 0 || strcmp('checkout', $view) == 0) {
    require_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . ucfirst($view) . '.php');
    $classname = ucfirst($view) . 'Controller' . $controller;
} else {
    // Create the controller
    $classname = 'EventgalleryController' . $controller;

}

/**
 * @var JControllerLegacy $controller
 */
$controller = new $classname();

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();



