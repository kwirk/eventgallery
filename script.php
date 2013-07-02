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

                #JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/models' );
                #JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/library' );
                #JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/views' );
                #JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/helpers' );
                #JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/controllers' );
                #JFolder::delete( JPATH_ROOT . '/components/com_eventgallery/media' );
                $filename = JPATH_ROOT . '/language/en-GB/en-GB.com_eventgallery.ini';
                if ( JFile::exists( $filename )) {
                  JFile::delete( $filename );
                  echo "removed $filename<br>";
                }
                $filename = JPATH_ROOT . '/administrator/language/en-GB/en-GB.com_eventgallery.ini';
                if ( JFile::exists( $filename )) {
                  JFile::delete( $filename );
                  echo "removed $filename<br>";
                }
                $filename = JPATH_ROOT . '/administrator/language/en-GB/en-GB.com_eventgallery.sys.ini';

                if ( JFile::exists( $filename )) {
                  JFile::delete( $filename );
                  echo "removed $filename<br>";
                }
                $filename = JPATH_ROOT . '/language/de-DE/de-DE.com_eventgallery.ini';
                if ( JFile::exists( $filename )) {
                  JFile::delete( $filename );
                  echo "removed $filename<br>";
                }
                $filename = JPATH_ROOT . '/administrator/language/de-DE/de-DE.com_eventgallery.ini';
                if ( JFile::exists( $filename )) {
                  JFile::delete( $filename );
                  echo "removed $filename<br>";
                }
                $filename = JPATH_ROOT . '/administrator/language/de-DE/de-DE.com_eventgallery.sys.ini';

                if ( JFile::exists( $filename )) {
                  JFile::delete( $filename );
                  echo "removed $filename<br>";
                }
                
        }

        /*
         * $parent is the class calling this method.
         * $type is the type of change (install, update or discover_install, not uninstall).
         * postflight is run after the extension is registered in the database.
         */
        function postflight( $type, $parent ) {
          
          /*      $imageFile = JPATH_ROOT . '/components/com_eventgallery/helpers/image.php';
                $blankFile = JPATH_ROOT . '/components/com_eventgallery/helpers/blank.php';
                $folder = JPATH_ROOT . '/components/com_eventgallery/helpers';
                $indexFile = JPATH_ROOT . '/index.php';
                echo $imageFile. ": ";
                echo substr(sprintf('%o', fileperms($imageFile)), -4);
                echo "<br>";
                echo $blankFile. ": ";
                echo substr(sprintf('%o', fileperms($blankFile)), -4);
                echo "<br>";
                echo $folder. ": ";
                echo substr(sprintf('%o', fileperms($folder)), -4);
                echo "<br>";
                
                chmod( $imageFile, 0644);
                chmod( $blankFile, 0644);
                chmod( $folder, 0755);
                
                echo $imageFile. ": ";
                echo substr(sprintf('%o', fileperms($imageFile)), -4);
                echo "<br>";
                echo $blankFile. ": ";
                echo substr(sprintf('%o', fileperms($blankFile)), -4);                
                echo "<br>";
                echo $folder. ": ";
                echo substr(sprintf('%o', fileperms($folder)), -4);
                echo "<br>";
                
                

                echo "access right update done";*/
        }
 
       
    
}