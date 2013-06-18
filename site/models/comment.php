<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


//jimport( 'joomla.application.component.helper' );

/** @noinspection PhpUndefinedClassInspection */
class EventgalleryModelComment extends JModelLegacy
{


    function getData($commentId)
    {
        $db = JFactory::getDBO();

        $query = ' SELECT * FROM #__eventgallery_comment ' .
            ' WHERE id=' . $db->Quote($commentId);
        $db->setQuery($query);
        return $db->loadObject();
    }

    function getFile($commentId)
    {
        $db = JFactory::getDBO();
        $comment = $this->getData($commentId);
        $query = ' SELECT * FROM #__eventgallery_file ' .
            ' WHERE file=' . $db->Quote($comment->file) . ' and folder=' . $db->Quote($comment->folder);
        $db->setQuery($query);
        return new EventgalleryHelpersImageLocal($db->loadObject());
    }
}