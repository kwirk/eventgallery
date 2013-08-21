<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

class EventgalleryHelpersRoute
{

    public static function createEventRoute($foldername, $tags)
    {
        $app = JFactory::getApplication();
        $menus = $app->getMenu('site');
        /**
         * @var JLanguage $lang
         */
        $lang = JFactory::getLanguage();
        $language = $lang->getTag();


        $component = JComponentHelper::getComponent('com_eventgallery');

        $attributes = array('component_id');
        $values = array($component->id);

// take the current lang into account
        $attributes[] = 'language';
        $values[] = array($language, '*');


        $items = $menus->getItems($attributes, $values);
        $itemid = NULL;

        foreach ($items as $item) {
            if (isset($item->query) && isset($item->query['view'])) {
                $view = $item->query['view'];

                if ($view == 'events') {
                    if (strlen($item->params->get('tags', '')) == 0) {
                        $itemid = $item->id;
                    } else {
                        if (EventgalleryHelpersTags::checkTags($item->params->get('tags'), $tags)) {
                            $itemid = $item->id;
                        }
                    }
                }

                if ($view == 'event' && isset($view->query['folder']) && $view->query['folder'] == $foldername) {
                    $itemid = $item->id;
                }
            }

            if ($itemid != NULL) {
                break;
            }
        }

// if not found, return language specific home link
        if ($itemid == NULL) {
            $default = $menus->getDefault($language);
            $itemid = !empty($default->id) ? $default->id : null;
        }

        $url = JRoute::_('index.php?option=com_eventgallery&view=event&folder=' . $foldername . '&Itemid=' . $itemid);

        return $url;
    }

}