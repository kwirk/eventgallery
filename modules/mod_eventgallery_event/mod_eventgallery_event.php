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

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_eventgallery/models', 'ContentModel');

// Load necessary media files
//EventgalleryHelpersMedialoader::load();


$foldername = $params->get('folder', null);

if ($foldername) {
    /**
     * @var EventModelEvent $model
     * */
    $model = JModelLegacy::getInstance('Event', 'EventModel', array('ignore_request' => true));
    $folder = $model->getFolder($foldername);



    if (isset($folder) && $folder->published==1 && EventgalleryHelpersFolderprotection::isAccessAllowed($folder) && EventgalleryHelpersFolderprotection::isVisible($folder)) {
        $files = $model->getEntries($foldername, -1, $params->get('max_images'), 1);
        require JModuleHelper::getLayoutPath('mod_eventgallery_event', $params->get('layout', 'default'));
    }
}