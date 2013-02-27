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
 * @param	array	An array of URL arguments
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 * @since	1.5
 */
function EventgalleryBuildRoute(&$query)
{
	$segments	= array();

	// get a menu item based on Itemid or currently active
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$params		= JComponentHelper::getParams('com_eventgallery');
	
	$segments = array();
	
	if(isset($query['view']))
	{
	        $segments[] = $query['view'];
	        unset( $query['view'] );
	}
	if(isset($query['folder']))
	{
	        $segments[] = $query['folder'];
	        unset( $query['folder'] );
	};
	if(isset($query['file']))
	{
	        $segments[] = $query['file']."/";
	        unset( $query['file'] );
	};
	

	return $segments;
}



/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 * @since	1.5
 */
function EventgalleryParseRoute($segments)
{
	$vars = array();

	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_eventgallery');
	$advanced = $params->get('sef_advanced_link', 0);
	$db = JFactory::getDBO();

	// Count route segments
	$count = count($segments);


	$vars['view']	= $segments[0];
	
	if ($count>1) {
		$vars['folder']	= str_replace(":","-",str_replace("/","",$segments[1]));
	}
	if ($count>2) {
		$vars['file']	= str_replace(":","-",str_replace("/","",$segments[2]));
	}
	
	
	return $vars;

}
