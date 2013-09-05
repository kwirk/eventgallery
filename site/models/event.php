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
jimport('joomla.html.pagination');


/** @noinspection PhpUndefinedClassInspection */
class EventModelEvent extends JModelLegacy
{
    protected $_pagination;

    function __construct()
    {
        $app = JFactory::getApplication();

        parent::__construct();

        $limitstart = JRequest::getInt('limitstart', 0);
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $this->setState('limit', $limit);
        $this->setState('com_eventgallery.event.limitstart', $limitstart);
    }

    function getEntries($folder = '', $limitstart = 0, $limit = 0, $imagesForEvents = 0)
    {
        if ($limit == 0) {
            $limit = $this->getState('limit');
        }

        if ($limitstart == 0) {
            $limitstart = $this->getState('com_eventgallery.event.limitstart');
        }

        // fix issue with events list where paging was working
        if ($limitstart < 0) {
            $limitstart = 0;
        }
        // do the picasa web handling here

        if (strpos($folder, '@') > -1) {
            $values = explode("@", $folder, 2);
            $obj_folder = $this->getFolder($folder);
            if ($obj_folder == NULL) {
                return NULL;
            }
            $picasakey = $obj_folder->picasakey;

            $album = EventgalleryHelpersImageHelper::picasaweb_ListAlbum($values[0], $values[1], $picasakey);

            if (count($album->photos) > 0) {
                return $limit > 0 ? array_slice($album->photos, $limitstart, $limit) : $album->photos;
            }

        }

        // database handling
        if ($imagesForEvents == 0) {
            // find files which are allowed to show in a list        
            $query
                = 'SELECT file.*, IF (isNull(comment.id),0,sum(1)) commentCount
            	  from #__eventgallery_file file join #__eventgallery_folder folder on folder.folder=file.folder and folder.published=1
            	  left join #__eventgallery_comment comment on file.folder=comment.folder and file.file=comment.file
                      where file.folder=' . $this->_db->Quote($folder) . '
                        and file.published=1
                        and (comment.published=1 or isNull(comment.published))
                        and file.ismainimageonly=0                    
                      group by file.folder, file.file
                      order by ordering desc, file.file';
        } else {
            // find files and sort them with the main images first
            $query
                = 'SELECT file.*, IF (isNull(comment.id),0,sum(1)) commentCount
              from #__eventgallery_file file join #__eventgallery_folder folder on folder.folder=file.folder and folder.published=1
              left join #__eventgallery_comment comment on file.folder=comment.folder and file.file=comment.file
                  where file.folder=' . $this->_db->Quote($folder) . '
                    and file.published=1
                    and (comment.published=1 or isNull(comment.published))                    
                  group by file.folder, file.file
                  order by file.ismainimage desc, ordering desc, file.file';
        }

        if ($limit != 0) {
            $entries = $this->_getList($query, $limitstart, $limit);
        } else {
            $entries = $this->_getList($query);
        }

        $result = Array();
        foreach ($entries as $entry) {
            
            $result[] = new EventgalleryHelpersImageLocal($entry);
        }


        return $result;
    }

    function getPagination($folder = '')
    {

        if (empty($this->_pagination)) {

            $total = $this->getTotal($folder);
            $limit = (integer)$this->getState('limit');
            $limitstart = $this->getState('com_eventgallery.event.limitstart');


            if ($limitstart > $total || JRequest::getVar('limitstart', '0') == 0) {
                $limitstart = 0;
                $this->setState('com_eventgallery.event.limitstart', $limitstart);
            }

            $this->_pagination = new JPagination($total, $limitstart, $limit);
        }

        return $this->_pagination;

    }

    function getTotal($folder = '')
    {
        if (strpos($folder, '@') > -1) {
            $values = explode("@", $folder, 2);
            $obj_folder = $this->getFolder($folder);
            if ($obj_folder == NULL) {
                return 0;
            }
            $picasakey = $obj_folder->picasakey;
            $album = EventgalleryHelpersImageHelper::picasaweb_ListAlbum($values[0], $values[1], $picasakey);
            return count($album->photos);
        } else {
            $query = 'select * from #__eventgallery_file where published=1 and ismainimageonly=0 and folder='
                . $this->_db->Quote($folder);
            return $this->_getListCount($query);
        }
    }

    /**
     * @param string $folder
     *
     * @return TableFolder
     */
    function getFolder($folder = '')
    {
        $query = 'SELECT * from #__eventgallery_folder where published=1 and folder=' . $this->_db->Quote($folder) . '';
        $folders = $this->_getList($query);
        if (count($folders) == 0) {
            return NULL;
        }

        $folder = $folders[0];
         // get the full text part
        $splittedText = EventgalleryHelpersTextsplitter::split($folder->text);

        $folder->text = $splittedText->fulltext;
        $folder->introtext = $splittedText->introtext;

        // Convert the params field to an array.
        $registry = new JRegistry;
        $registry->loadString($folder->attribs);
        $folder->attribs = $registry;

        return $folder;
    }


}
