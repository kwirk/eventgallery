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
//EventgalleryHelpersMedialoader::load();


$foldername = $params->get('folder', null);

if ($foldername) {
   
	/**
     * @var EventgalleryLibraryManagerFolder $folderMgr
     */
    $folderMgr = EventgalleryLibraryManagerFolder::getInstance();            
    $folder = $folderMgr->getFolder($foldername);


    if (isset($folder) && $folder->isPublished() && EventgalleryHelpersFolderprotection::isAccessAllowed($folder) && $folder->isVisible()) {
        $files = $folder->getFiles(-1, $params->get('max_images'), 1);
        require JModuleHelper::getLayoutPath('mod_eventgallery_event', $params->get('layout', 'default'));
    }
}