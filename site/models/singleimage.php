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

class EventgalleryModelSingleimage extends JModelLegacy
{

    var $folder = null;
	var $file= null;
	var $nextFile = null;
	var $prevFile = null;
	var $nextFiles = Array();
	var $prevFiles = Array();
	var $firstFile = null;
	var $lastFile = null;
	var $position = 0;
	var $overallcount = 0;
	var $comments = null;
	var $_dataLoaded = false;
	var $currentLimitStart = 0;
	
	function __construct()
	{
	    $app = JFactory::getApplication();
	    
	    $params = JComponentHelper::getParams('com_eventgallery');
        $this->paging_images_count = $params->get('paging_images_count');
	    
	    parent::__construct();	    
		$limit =  $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$this->setState('limit',$limit);

	}
	
	
	function getData($folder, $filename)
	{
	    if (!$this->_dataLoaded)
	    {
	    	$this->loadFolder($folder);
	    	
	    	$files = Array();
	    	
            // picasa files are not stored in the database
            $countHits = true;

	    	if (strpos($folder,'@')>-1) {
				$values = explode("@",$folder,2);
				$picasakey = $this->folder->picasakey;
				$album = EventgalleryHelpersImageHelper::picasaweb_ListAlbum($values[0], $values[1], $picasakey);
				$files = $album->photos;
                $countHits = false;
			} else {
	    	
	    	    $db = JFactory::getDBO();
	            $query = 'SELECT * from #__eventgallery_file 
	                      where folder='.$db->Quote($folder).' and published=1 order by file';
	            $db->setQuery($query);
	            #$db = new JDatabase();
	            $files = $db->loadObjectList();
	            
	            $filesObjects = Array();
	            
	            foreach($files as $file) {
	            	$filesObjects[] = new EventgalleryHelpersImageLocal($file);
	            }
	            
	            $files = $filesObjects;
        	}
        	
            $i=0;
            $filesCount = count($files);


            foreach($files as $file)
            {
                if (strcmp($file->file,$filename)==0)
                {
                    /**
                     * Update Hits
                     */
                    if ($countHits == true) {
                        $table = $this->getTable('File');
                        $table->bind($file);
                        $table->hits++;
                        $table->store(); 
                    }
            
                    /**
                     * Set Data
                     */
                    $this->_dataLoaded = true;
            		$this->file = $file;
            		$this->prevFile = $files[max(0,$i-1)];
                    $this->nextFile = $files[min($filesCount-1,$i+1)];
                    
                    
                    
                    $lower = floor($this->paging_images_count/2);
                    $upper = $this->paging_images_count - $lower;

                	$lowerStop = $i-1;
                	$upperStart = $i+1;

                    
                    /**
                      Wenn weniger Bilder da sind, als für das BildPaging gebraucht werden
                    */
                    if ($filesCount-1 < $this->paging_images_count)
                    {
                    	$lowerStart = 0;
                    	$upperStop = $filesCount-1;
                    }
                    /* Wenn genug Bilder da sind*/
                    else {
                    	$lowerStart = $i-$lower;
                    	$upperStop = $i+$upper;
                    	
                    	if ($lowerStart<0) {
                    		$upperStop += 0-$lowerStart;
                    		$lowerStart = 0;
                    	}
                    	
                    	if ($upperStop>=$filesCount) {                
                    		$lowerStart = $lowerStart-($upperStop-$filesCount)-1;
                    		$upperStop = $filesCount-1;
                    	}

                    }
                    
                    
                    $this->nextFiles = array_slice($files,$upperStart,$upperStop-$upperStart+1);
                    if ($lowerStop>=0)
                    	$this->prevFiles = array_slice($files,$lowerStart,$lowerStop-$lowerStart+1);
                  /*
                    echo "lower  = array_slice($files,$lowerStart,$lowerStop+$lowerStart-2);<br>";
                    echo "upper = array_slice($files,$lowerStart,$lowerStop-$lowerStart+1);<br>";
                    
                    //echo "<pre>";
                    echo "$i\n<br>";
                    echo "$lower -> $upper\n<br>";
                    echo "$lowerStart -> $lowerStop\n<br>";
                    echo "$upperStart -> $upperStop<br>";
                    echo count($this->prevFiles)."<br>";
                    echo count($this->nextFiles)."<br>";
                    */
                    
                    
                    $this->lastFile = $files[count($files)-1];
                    $this->firstFile = $files[0];
            		$this->loadComments();
            		$this->overallcount = count($files);
                    $this->position = $i+1;
                    
                    if ($this->getState('limit') > 0 )
                    	$this->currentLimitStart = $i-($i%$this->getState('limit'));
                    else
                    	$this->currentLimitStart=0;

                    
                }
                
                
                $i++;
            }
            
	    }
	}

    function loadFolder($folder)
    {
        if (!$this->folder)
        {        	
        	$query = 'SELECT * from #__eventgallery_folder 
        	          where published=1 and folder='.$this->_db->Quote($folder);
        	$folders = $this->_getList($query);
        	$this->folder = $folders[0];
        }
    }
    
    function loadComments()
    {
    	if (!$this->comments)
    	{    		
	    	$query = "SELECT * 
	    			  FROM #__eventgallery_comment 
	    	          WHERE published=1 
	    	    			and file=".$this->_db->Quote($this->file->file)."
	    	     			and folder=".$this->_db->Quote($this->file->folder)."
	    	          ORDER BY date DESC";    
	    	$this->comments = $this->_getList($query);
	    	if (!$this->comments)
	    	{
	    		$this->comments = Array();
	    	}
	    }
    }
    
  	function store_comment($data,$published)
    {
        $entry  = $this->getTable('Comment');

        if (!$entry->bind($data, "published")) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        
        $entry->date = date("Y.m.d H:i:s",time());
        $entry->published=$published;
        $entry->user_id = JRequest::getVar( "user_id", '0', 'COOKIE', 'INT');
        $entry->ip = $_SERVER['REMOTE_ADDR'];
        
        if (!$entry->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            die();
            return false;
        }
        
        return $entry;
    }
    
    function getBuzzwords()
    {
        /*$query = "SELECT * from #__buzzword where published=1";
        $buzzwordList = $this->_getList($query);
        $buzzwords = Array();
        if (is_array($buzzwordList))
        {
        	foreach($buzzwordList as $buzzword)
	        {
	            array_push($buzzwords, $buzzword->buzzword);
	        }
        }*/
        $buzzwords = array();
        return $buzzwords;
        
    } 
    
}