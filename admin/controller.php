<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/** @noinspection PhpUndefinedClassInspection */
class EventgalleryController extends JControllerLegacy
{
	
	protected $default_view = 'events';


    /**
     * constructor (registers additional tasks to methods)
     * @return \EventgalleryController
     */
	function __construct()
	{
		parent::__construct();	
	}
	/*
	 * Standard display method
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  A JControllerLegacy object to support chaining.

	 */
	public function display($cachable = false, $urlparams = false)
	{
		parent::display($cachable, $urlparams);
	}

	/**
	 * function to call comments-View
	 * 
	 * @return void
	 */
	function comments()
	{
		
		EventgalleryHelpersEventgallery::addSubmenu(JRequest::getCmd('view', 'comments'));
		$view = $this->getView('comments','html');
		$view->setModel($this->getModel('comments'),true);
		$view->display();
	}

	/**
	 * method to call edit-Events-View
	 * 
	 * @return void
	 */
	function editComment()
	{
		JRequest::setVar( 'view', 'comment' );
		JRequest::setVar('hidemainmenu', 1);
		$this->display();
	}

	/**
	 * method to save a comment after editing it
	 */
	function saveComment()
	{
		
		JRequest::setVar( 'view', 'comment' );
		$model = $this->getModel('comment');
        $post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_SAVED_SUCCESS' );
		} else {
			$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_SAVED_ERROR' );
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$this->setRedirect( 'index.php?option=com_eventgallery&task=comments&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}

	/**
	 * function to remode a comment
	 * 
	 * @return void
	 */
	function removeComment()
	{

		$model = $this->getModel('comment');
		if(!$model->delete()) {
			$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_DELETE_ERROR' );
		} else {
			$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_DELETE_SUCCESS' );
		}

		$this->setRedirect( 'index.php?option=com_eventgallery&task=comments&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}

	/**
	 * function to cancel editing of a comment
	 */
	function cancelComment()
	{
		$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_EDIT_CANCEL' );
		$this->setRedirect( 'index.php?option=com_eventgallery&task=comments&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}

	/*
	 * function to publish a comment
	 */
	function Commentpublish()
	{
		$model = $this->getModel('comment');
		$model->publish(1);
		$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_PUBLISHED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&task=comments&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}
	
	/**
	 * function to unpublish a comment
	 */
	function Commentunpublish()
	{
		$model = $this->getModel('comment');
		$model->publish(0);
		$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_UNPUBLISHED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&task=comments&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}
	
	
	/**
	 * function to refresh the database-content. It syncs the content 
	 * of the filesystem with content of the database
	 */
	function refreshDatabase()
	{
		
		
	    $db = JFactory::getDBO();
	    $user = JFactory::getUser();	
		#$db = new JDatabase();
  		$maindir=JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
  		
  		if (file_exists($maindir)) {
		$verzeichnis = dir($maindir);
		} else {
			$msg = JText::sprintf('COM_EVENTGALLERY_SYNC_DATABASE_FOLDER_NOT_FOUND', $maindir);
			$this->setRedirect( 'index.php?option=com_eventgallery', $msg );
			return;
		}
		
		# Update Verzeichnisse
		
		$folders = Array();
		
		# Hole die verfügbaren Verzeichnisse
		while ($elm = $verzeichnis->read()) 
		{ //sucht alle Verzeichnisse mit Bilder
		    if (is_dir($maindir.$elm) && !preg_match("/\./",$elm) && !preg_match("/.cache/",$elm)) 
		    {
			    if (is_dir($maindir.$elm.DIRECTORY_SEPARATOR )) 
			    {
			    	array_push($folders, $elm);
			   	}
			}
		}
		
		# Lösche nicht mehr vorhandene Verzeichnisse aus der Datenbank, lasse alle Verzeichnisse in Ruhe, die ein @ haben
		
		$query = "delete from  #__eventgallery_folder where folder not like '%@%' and folder not in ('".implode('\',\'',$folders)."')";
		$db->setQuery($query);
		$db->query();
		$query = "delete from #__eventgallery_file where folder not in ('".implode('\',\'',$folders)."')";
		$db->setQuery($query);
		$db->query();
		
		# Füge Verzeichnisse in die DB ein
		foreach($folders as $folder)
		{
			#Versuchen wir, ein paar Infos zu erraten
			
			$date = "";
			$temp = array();

			if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$folder, $temp))
			{
				$date = $temp[0];
				$description = str_replace($temp[0],'',$folder);
			}
			else {
				$description = $folder;
			}

			$description = trim(str_replace("_", " ", $description));
			
			$query = "insert IGNORE into #__eventgallery_folder 
			            set folder=".$db->Quote($folder).", 
			                 published=0,
			                 date=".$db->Quote($date).",
			                 description=".$db->Quote($description).",
			                 userid=".$db->Quote($user->id)."
			         ;";
			$db->setQuery($query);
			$db->query();
			
		}
		
		# Update Files pro Verzeichnis
		
		
		foreach($folders as $folder)
		{
			$files = Array();
			set_time_limit(120);
			
			# Hole alle Dateien eines Verzeichnisses
			$dir=dir($maindir.$folder);
	    	while ($elm = $dir->read()) 
	    	{
	    		if (is_file($maindir.$folder.DIRECTORY_SEPARATOR.$elm))
				array_push($files, $elm);			
    		}		
    		
    		# Lösche nicht mehr vorhandene Files eines Verzeichnisses aus der DB
    		$query = "delete from  #__eventgallery_file where folder='$folder' and file not in ('".implode('\',\'',$files)."')";
			$db->setQuery($query);
			$db->query();
			
			# Füge alle Dateien eines Verzeichnisses in die DB ein.
			foreach($files as $file)
			{
				@list($width, $height, $type, $attr) = getimagesize($maindir.$folder.DIRECTORY_SEPARATOR.$file);

				
				$query = "insert IGNORE into #__eventgallery_file set folder='$folder', file='$file', width='$width', height='$height', published=1";
				$db->setQuery($query);
				$db->query();
				
				EventgalleryController::updateMetadata($maindir.$folder.DIRECTORY_SEPARATOR.$file, $folder, $file);
			}
		}
		
		
		$msg = JText::_( 'COM_EVENTGALLERY_SYNC_DATABASE_SYNC_DONE' );
		$this->setRedirect( 'index.php?option=com_eventgallery', $msg );	
	
	}


	
	/**
	 * function to publish a file. This is uses for links in emails
	 * 
	 */
	function publishFileByMail()
	{
		$model = $this->getModel('file');
		$model->publish(1);
		$view = $this->getView('publishFileByMail','raw');
		$view->setModel($model, true);
		$view->display();
	
	}
	
	/**
	 * function to unpublish a file. This is uses for links in emails
	 * 
	 */
	function unpublishFileByMail()
	{
		$model = $this->getModel('file');
		$model->publish(0);
		$view = $this->getView('publishFileByMail','raw');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * function to unpublish a comment. This is uses for links in emails
	 * 
	 */
	function unpublishCommentByMail()
	{
		$model = $this->getModel('comment');
		$model->publish(0);
		$view = $this->getView('publishCommentByMail','raw');
		$view->setModel($model, true);
		$view->display();
	}
	
	  

	
	/**
	 * function to disallow comments for a file
	 */
	function disallowCommentsForFileByMail()
	{
		$model = $this->getModel('file');
		$model->allowComments(0);
		$view = $this->getView('allowCommentsForFileByMail','raw');
		$view->setModel($model, true);
		$view->display();
	
	}
	
	
	


	
	
	/**
	 * function so remove every cache-entry
	 */
	function clearCache()
	{
		
		$path=JPATH_BASE.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'com_eventgallery';
		rrmDir($path);
		$msg = JText::_( 'COM_EVENTGALLERY_CLEAR_CACHE_DONE' );
		$this->setRedirect( 'index.php?option=com_eventgallery', $msg );
	}
	
	/**
     * upaded meta information
     */
    public static function updateMetadata($path, $foldername, $filename) {

        $libPath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'pel'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
        require_once($libPath.'PelJpeg.php');
        require_once($libPath.'PelTiff.php');

        @list($width, $height, $type, $attr) = getimagesize($path);
        $input_jpeg = new PelJpeg($path);

        $app1 = $input_jpeg->getExif();

        if ($app1) {
            $tiff = $app1->getTiff();
            $ifd0 = $tiff->getIfd();
            $exifData = $ifd0->getSubIfd(PelIfd::EXIF);


            $exif = array();
            if ($exifData) {
                if ($data = $exifData->getEntry(PelTag::APERTURE_VALUE)) {
                    $value = $data->getValue();
                    $exif['fstop'] = sprintf('%.01f',pow(2, $value[0]/$value[1]/2));
                }
                if ($data = $exifData->getEntry(PelTag::FOCAL_LENGTH)) {
                    $value = $data->getValue();
                    $exif['focallength'] = sprintf('%.0f',$value[0]/$value[1]);
                }
                if ($data = $ifd0->getEntry(PelTag::MODEL)) {
                    $exif['model'] = $data->getText();
                }
                if ($data = $exifData->getEntry(PelTag::ISO_SPEED_RATINGS)) {
                    $exif['iso'] = $data->getText();
                }
            }


        }

        $exifJson = json_encode($exif);

        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->update("#__eventgallery_file");
        $query->set("width=".$db->quote($width));
        $query->set("height=".$db->quote($height));
        $query->set("exif=".$db->quote($exifJson));
        $query->where('folder='.$db->quote($foldername));
        $query->where('file='.$db->quote($filename));
        $db->setQuery($query);
        $db->execute();

        Pel::clearExceptions();
        unset($input_jpeg);



    }

	
}

function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            rrmdir($file);
        else
            unlink($file);
    }
    rmdir($dir);
}
