<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


//the name of the class must be the name of your component + InstallerScript
//for example: com_contentInstallerScript for com_content.
class com_eventgalleryInstallerScript
{
        /*
         * $parent is the class calling this method.
         * $type is the type of change (install, update or discover_install, not uninstall).
         * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
         * If preflight returns false, Joomla will abort the update and undo everything already done.
         */
        function preflight( $type, $parent ) {

                JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/models' );
                JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/library' );
                JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/views' );
                JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/helpers' );
                JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/controllers' );
                JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/media' );
                
        }
 
       
    
}