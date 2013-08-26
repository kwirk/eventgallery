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

/**
 * @var JCacheControllerCallback $cache
 */
$cache = JFactory::getCache('mod_eventgallery_latest');

$user = JFactory::getUser();
$usergroups = JUserHelper::getUserGroups($user->id);

/**
 * @var EventsModelEvents $eventsModel
 * */
$eventsModel = JModelLegacy::getInstance('Events', 'EventsModel', array('ignore_request' => true));

$events = $cache->call(
    array($eventsModel, 'getEntries'),
        -1,
        $params->get('max_images', 5),
        $params->get('tags', ''),
        $params->get('sort_events_by', 'ordering'),
        $usergroups
);

$position = $params->get('event_history_position', 0);

if (count($events)>$position) {
    $foldername = $events[$position]->folder;
    /**
     * @var EventModelEvent $model
     * */
    $model = JModelLegacy::getInstance('Event', 'EventModel', array('ignore_request' => true));
    $folder = $model->getFolder($foldername);

    if (isset($folder) && $folder->published==1 && EventgalleryHelpersFolderprotection::isAccessAllowed($folder)) {
        $files = $model->getEntries($foldername, 0, $params->get('max_images'), 1);
        require JModuleHelper::getLayoutPath('mod_eventgallery_latest', $params->get('layout', 'default'));
    }
}