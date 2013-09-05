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
        $app = JFactory::getApplication();
        
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
     * @param $usergroups
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
            $query = 'SELECT folder.*, count(1) overallCount 
                      FROM #__eventgallery_folder folder left join #__eventgallery_file file on 
                            folder.folder = file.folder AND folder.published=1 AND file.published=1                         
                      WHERE folder.published=1 
                        and (isnull(file.ismainimageonly) OR file.ismainimageonly=0)
                      GROUP by folder.folder ';

            if ($sortAttribute == "date_asc") {
                $query .= 'ORDER by date asc, ordering desc';            
            } elseif ($sortAttribute == "date_desc") {
                $query .= 'ORDER by date desc, ordering desc';            
            } elseif ($sortAttribute == "name_asc") {
                $query .= 'ORDER by folder.folder asc';            
            } elseif ($sortAttribute == "name_desc") {
                $query .= 'ORDER by folder.folder desc';            
            } else {
                $query .= 'ORDER by ordering desc';
            }
            
            $entries = $this->_getList($query);


            $unsetList = Array();
            if (count($entries)>0)
            {
                foreach ($entries as $rownum=>$entry)
                {
                    
                    if (strpos($entry->folder,'@')>-1) {
                        $values = explode("@",$entry->folder,2);
                        $album = EventgalleryHelpersImageHelper::picasaweb_ListAlbum($values[0], $values[1], $entry->picasakey);
                        if (count($album->thumbs)>0) {
                            $entries[$rownum]->overallCount = $album->overallCount;
                            $entries[$rownum]->thumbs = $album->thumbs;
                            $entries[$rownum]->titleImage = new EventgalleryHelpersImagePicasa($album);
                            
                        } else {
                            array_push($unsetList, $rownum);
                        }
                    } else {
                        $entries[$rownum]->commentCount = $this->getCommentCount($entry->folder);                   
                    }
                }
            }
            
            // remove empty picasa albums
            foreach ($unsetList as $entry) {
                unset($entries[$entry]);
            }
            
            if (strlen($tags)!=0) {
                
                // remove all non matching entries

                // handle space and comma separated lists like "foo bar" or "foo, bar"

                
                $finalWinners = Array();
                
                foreach($entries as $entry) {
                    if (EventgalleryHelpersTags::checkTags($tags, $entry->foldertags) ) {
                        $finalWinners[] = $entry;
                    }
                }

                $entries = $finalWinners;
            }

            // filter by user group
            foreach($entries as $key=>$entry) {
                if (!EventgalleryHelpersFolderprotection::isVisible($entry)) {
                    unset($entries[$key]);
                }
            }


            foreach($entries as $entry) {

                $splittedText = EventgalleryHelpersTextsplitter::split($entry->text);

                $entry->text = $splittedText->fulltext;
                $entry->introtext = $splittedText->introtext;
            }

            $this->_entries = $entries;
            $this->_total = count($entries);

            
            
        }

        return array_slice($this->_entries, $limitstart, $limit);
        
    }

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
    
    function getCommentCount($folder)
    {
        if (!$this->_commentCount)
        {
            $query = 'select folder, count(1) commentCount 
                      from #__eventgallery_comment
                      where published=1
                      group by folder';
            $comments = $this->_getList($query,0,0);
            $this->_commentCount = array();
            foreach($comments as $comment)
            {
                $this->_commentCount[$comment->folder] = $comment->commentCount;
            }
        }
        
        return @$this->_commentCount[$folder]+0;
    }

    function getFolderCount()
    {
        $db = JFactory::getDBO();
        $query = 'SELECT count(1) from #__eventgallery_folder where published=1';
        $db->setQuery( $query );
        return $db->loadResult();
    }
    
    function getFileCount()
    {
        $db = JFactory::getDBO();
        $query = 'SELECT count(1) from #__eventgallery_file file join #__eventgallery_folder folder 
                    on file.folder=folder.folder 
                    where file.published=1 and folder.published=1';
        $db->setQuery( $query );
        return $db->loadResult();
    }

}