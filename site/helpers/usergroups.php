<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

class EventgalleryHelpersUsergroups
{

    static $usergroups;


    /**
    * Returns an array of id=>name
    */
    public static function getUserGroups()
    {
        if (self::$usergroups == null ) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('a.id AS value, a.title AS text')
            ->from('#__usergroups AS a');


        // Get the options.
        $db->setQuery($query);
        $objects = $db->loadObjectList();
        $usergroups = array();
        foreach($objects as $object) {
            $usergroups[$object->value] = $object->text;
        }

        self::$usergroups = $usergroups;

       }

       return self::$usergroups;
    }

    /**
    * Resolve an usergroup id into a name
    */
    public static function getUserGroupName($id) {
        $usergroups = self::getUserGroups();
        return $usergroups[$id];
    }

}