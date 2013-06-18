<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Build the route for the com_eventgallery component
 *
 * @param    array $query An array of URL arguments
 *
 * @return    array    The URL arguments to use to assemble the subsequent URL.
 * @since    1.5
 */
function EventgalleryBuildRoute(&$query)
{

    // get a menu item based on Itemid or currently active
    $config = JFactory::getConfig();
    $segments = array();

    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['folder'])) {
        $segments[] = $query['folder'];
        unset($query['folder']);
    }
    if (isset($query['file'])) {
        /*take care of the appended html. This will not work with file names*/
        if ($config->get('sef_suffix') == 1) {
            $segments[] = $query['file'] . "/file";

        } else {
            $result = preg_replace("/\.(.{3,4}$)/i", "-\\1", $query['file']);
            $segments[] = $result . "/";

        }
        unset($query['file']);

    };

    return $segments;
}


/**
 * Parse the segments of a URL.
 *
 * @param    array $segments The segments of the URL to parse.
 *
 * @return    array    The URL attributes to be used by the application.
 * @since    1.5
 */
function EventgalleryParseRoute($segments)
{
    $vars = array();

    // Count route segments
    $count = count($segments);


    $vars['view'] = $segments[0];

    if ($count > 1) {
        $vars['folder'] = str_replace(":", "-", str_replace("/", "", $segments[1]));
    }
    if ($count > 2) {

        $result = str_replace(":", "-", str_replace("/", "", $segments[2]));
        $result = preg_replace("/-(.{3,4}$)/i", ".\\1", $result);
        $vars['file'] = $result;
    }

    return $vars;

}
