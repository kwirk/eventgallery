<?php
/**
 * @package     Sven.Bluege
 * @subpackage  mod_eventgallery_cart
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
// Include the syndicate functions only once

//load classes
JLoader::registerPrefix('Eventgallery', JPATH_BASE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_eventgallery');

// Load necessary media files
EventgalleryHelpersMedialoader::load();
$params = JComponentHelper::getParams('com_eventgallery');


/**
 * Only load the cart if the internal cart is disabled or we're currently not showing the gallery component
 */
$use_cart = $params->get('use_cart_inside_component', '1') == 0 || JRequest::getString('option')!='com_eventgallery';

$use_cart = true;
if ($use_cart) {
    require JModuleHelper::getLayoutPath('mod_eventgallery_cart', $params->get('layout', 'default'));
}
