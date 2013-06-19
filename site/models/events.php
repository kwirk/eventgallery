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

class EventgalleryModelEvents extends JModelLegacy
{

    var $_commentCount = null;
    var $_total = 0;
    var $_entries = null;
    var $_pagination;

    function __construct()
    {
        $app = JFactory::getApplication();
        
        parent::__construct();      
        
        $limitstart =  $app->getUserStateFromRequest( 'com_eventgallery.events.limitstart', 'limitstart', 0);        

        $limit =  $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
        $this->setState('limit',$limit);
        $this->setState('com_eventgallery.events.limitstart',$limitstart);
    }
    
    function getEntries($limitstart=0, $limit=0, $tags = "", $sortAttribute='ordering')
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
                        if (count($album)>0) {
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

                

                $tempTags = explode(',',str_replace(" ", ",", $tags));        
                array_walk($tempTags, 'trim');
                
                $tags = Array();

                foreach($tempTags as $tag)
                {
                    
                    if(strlen($tag)>0)
                    {
                        array_push($tags,$tag);
                        
                    }
                }        
                
                $regex = "/(".implode($tags,'|').")/i";
                
                $finalWinners = Array();
                
                foreach($entries as $entry) {
                    if (preg_match($regex, $entry->tags)) {
                        $finalWinners[] = $entry;
                    }
                }

                $entries = $finalWinners;
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

            $limit      = $this->getState('limit');
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