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

jimport( 'joomla.application.component.model' );


//jimport( 'joomla.application.component.helper' );

class EventsModelEvents extends JModelLegacy
{

    var $_commentCount = null;
    var $_total = 0;
    var $_entries = null;
    var $_pagination;

    function __construct()
    {
        parent::__construct();      

        $limitstart =  JRequest::getInt('limitstart');
        $limit =  JComponentHelper::getParams('com_eventgallery')->get('max_events_per_page', 12);
        $this->setState('limit',$limit);
        $this->setState('com_eventgallery.events.limitstart',$limitstart);
    }

    /**
     *
     *
     * @param int $limitstart
     * @param int $limit
     * @param string $tags
     * @param string $sortAttribute
     * @param $usergroups even if unused we need this for the cache call
     * @return array
     */
    function getEntries($limitstart=0, $limit=0, $tags = "", $sortAttribute='ordering', $usergroups)
    {


        if($limit==0)  {
            $limit = $this->getState('limit');
        } else {
            $this->setState('limit',$limit);
        }
        
        if($limitstart==0) {
            $limitstart = $this->getState('com_eventgallery.events.limitstart');
        }

        // fix issue with events list where paging was working
        if($limitstart <0 ) {
            $limitstart = 0;
        }

        if ($this->_entries == null) {
            $query = $this->_db->getQuery(true)
                ->select('folder.*, count(1) AS '.$this->_db->quoteName('overallCount'))
                ->from($this->_db->quoteName('#__eventgallery_folder') . ' AS folder')
                ->join('LEFT', $this->_db->quoteName('#__eventgallery_file') . ' AS file ON folder.folder = file.folder AND folder.published=1 AND file.published=1')
                ->where('(file.ismainimageonly IS NULL OR file.ismainimageonly=0)')
                ->group('folder.id');

            if ($sortAttribute == "date_asc") {
                $query->order('date ASC, ordering DESC');
            } elseif ($sortAttribute == "date_desc") {
                $query->order('date DESC, ordering DESC');
            } elseif ($sortAttribute == "name_asc") {
                $query->order('folder.folder ASC');
            } elseif ($sortAttribute == "name_desc") {
                $query->order('folder.folder DESC');
            } else {
                $query->order('ordering DESC');
            }
            
            $entries = $this->_getList($query);


            $newList = Array();
            /**
             * @var EventgalleryLibraryManagerFolder $folderMgr
             */
            $folderMgr = EventgalleryLibraryManagerFolder::getInstance();

            foreach ($entries as $rownum=>$entry)
            {
                $entryObject = $folderMgr->getFolder($entry);
                if ($entryObject->getFileCount()>0) {
                     array_push($newList, $entryObject);
                }
            }

            
            $entries = $newList;
            
            if (strlen($tags)!=0) {
                
                // remove all non matching entries
                // handle space and comma separated lists like "foo bar" or "foo, bar"

                
                $finalWinners = Array();
                
                foreach($entries as $entry) {
                    if (EventgalleryHelpersTags::checkTags($tags, $entry->getFolderTags()) ) {
                        $finalWinners[] = $entry;
                    }
                }

                $entries = $finalWinners;
            }

            /**
             * @var EventgalleryLibraryFolder $entry
             */
            // filter by user group
            foreach($entries as $key=>$entry) {
                if (!$entry->isVisible()) {
                    unset($entries[$key]);
                }
            }

            $this->_entries = $entries;
            $this->_total = count($entries);

            
            
        }

        return array_slice($this->_entries, $limitstart, $limit);
        
    }

    /**
     * returns the paging bar for the current data set.
     *
     * @return JPagination
     */
    function getPagination()
    {

        if (empty($this->_pagination))
        {
            
            $total = $this->_total;

            /**
             * @var integer $limit
             */
            $limit      = $this->getState('limit');

            /**
             * @var integer $limitstart
             */
            $limitstart = $this->getState('com_eventgallery.events.limitstart');
     

            if ($limitstart > $total || JRequest::getVar('limitstart','0')==0) {
                $limitstart=0;             
                $this->setState('com_eventgallery.event.limitstart',$limitstart);
            }
            
            $this->_pagination = new JPagination($total, $limitstart, $limit);
        }
        
        return $this->_pagination;
        
    }

}
