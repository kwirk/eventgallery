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

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__eventgallery_comment'))
            ->where('id=' . $db->quote($commentId));
        $db->setQuery($query);
        return $db->loadObject();
    }

    function getFile($commentId)
    {
        $db = JFactory::getDBO();
        $comment = $this->getData($commentId);
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__eventgallery_file'))
            ->where('file=' . $db->quote($comment->file))
            ->where('folder=' . $db->quote($comment->folder));
        $db->setQuery($query);
        return new EventgalleryHelpersImageLocal($db->loadObject());
    }
}
