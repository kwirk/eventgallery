<?php

jimport('joomla.application.component.controller');


class EventgalleryController extends JControllerLegacy
{
	
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_EVENTS'),
			'index.php?option=com_eventgallery',
			$vName == 'events'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_COMMENTS'),
			'index.php?option=com_eventgallery&view=comments',
			$vName == 'comments');

		JSubMenuHelper::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_ORDERS'),
			'index.php?option=com_eventgallery&view=orders',
			$vName == 'orders');
		
		JSubMenuHelper::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_CLEAR_CACHE'),
			'index.php?option=com_eventgallery&task=clearCache',
			$vName == 'clearCache'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_EVENTGALLERY_SUBMENU_SYNC_DATABASE'),
			'index.php?option=com_eventgallery&task=refreshDatabase',
			$vName == 'refreshDatabase'
		);
	}
	
	
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'newFolder'  , 'editEvent' );
		$this->registerTask( 'edit'  , 	'editEvent' );
		$this->registerTask( 'applyEvent', 'saveEvent');
		
	}
	/*
	 * Standard display method
	 */
	public function display($cachable = false, $urlparams = false)
	{
		
		
		EventgalleryController::addSubmenu(JRequest::getCmd('view', 'events'));
		parent::display($cachable, $urlparams);
	}

	/**
	 * function to call comments-View
	 * 
	 * @return void
	 */
	function comments()
	{
		
		EventgalleryController::addSubmenu(JRequest::getCmd('view', 'comments'));
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
	 * @return unknown_type
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
	 * @return unknown_type
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
	 * @return unknown_type
	 */
	function Commentunpublish()
	{
		$model = $this->getModel('comment');
		$model->publish(0);
		$msg = JText::_( 'COM_EVENTGALLERY_COMMENT_UNPUBLISHED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&task=comments&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}

	/**
	 * function to publish an event
	 * 
	 * @return unknown_type
	 */
	function publish()
	{
		$model = $this->getModel('folder');
		$model->publish(1);
		JText::_( 'COM_EVENTGALLERY_EVENT_PUBLISHED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}
	
	/**
	 * function tu unpublish an event
	 * 
	 * @return unknown_type
	 */
	function unpublish()
	{
		$model = $this->getModel('folder');
		$model->publish(0);
		$msg = JText::_( 'COM_EVENTGALLERY_EVENT_UNPUBLISHED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
	}
	
	/**
	 * function to refresh the database-content. It syncs the content 
	 * of the filesystem with content of the database
	 * @return unknown_type
	 */
	function refreshDatabase()
	{
		
		
	    $db =& JFactory::getDBO();
	    $user =& JFactory::getUser();	
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
			$description = "";
			$temp = array();

			if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$folder, $temp))
			{
				$date = $temp[0];
				$description = str_replace($temp[0],'',$folder);
			}
			else {
				$temp = $folder;
			}
			
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
				
				$query = "update #__eventgallery_file set width='$width', height='$height' where folder='$folder' and file='$file'";
				$db->setQuery($query);
				$db->query();
			}
		}
		
		
		$msg = JText::_( 'COM_EVENTGALLERY_SYNC_DATABASE_SYNC_DONE' );
		$this->setRedirect( 'index.php?option=com_eventgallery', $msg );	
	
	}
	
	/**
	 * function to edit an event
	 * @return unknown_type
	 */
	function editEvent()
	{
		JRequest::setVar( 'view', 'event' );
		JRequest::setVar('hidemainmenu', 1);
		$this->display();
	}

	/**
	 * function to delete an event. It removes every file from the 
	 * filesystem and deletes all content excluding comments from the database.
	 * 
	 * @return unknown_type
	 */
	function removeEvent()
	{

		$model = $this->getModel('event');
		if(!$model->delete()) {
			$msg = JText::_( 'COM_EVENTGALLERY_EVENT_DELETE_ERROR' );
		} else {
			$msg = JText::_( 'COM_EVENTGALLERY_EVENT_DELETE_SUCCESS' );
		}

		$this->setRedirect( 'index.php?option=com_eventgallery&view=eventgallery', $msg );
	}
	
	/**
	 * function to save an event after editing
	 * 
	 * @return unknown_type
	 */
	function saveEvent()
	{
		
		JRequest::setVar( 'view', 'event' );
		$model = $this->getModel('event');
		$model->setId(JRequest::getVar('id'));
		$item = $model->getData();
		$oldFolder = $item->folder;
		
		if ($model->store($post)) {
			$msg = JText::_( 'COM_EVENTGALLERY_EVENT_SAVED_SUCCESS' );
		} else {
			$msg = JText::_( 'COM_EVENTGALLERY_EVENT_SAVED_ERROR' );
		}
				
		$item = $model->getData();
		$newFolder = $item->folder;
		
		# Rename folder now:
		
		$basedir=JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery'.DIRECTORY_SEPARATOR ;
		if (strcmp($oldFolder, $newFolder)!=0)
		{
			rename($basedir.$oldFolder, $basedir.$newFolder);
			$model->changeFolderName($oldFolder, $newFolder);
		}		

		$task	= JRequest::getCmd( 'task' );
		switch ($task)
		{
			case 'applyEvent' :
				$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&cid[]='.JRequest::getVar('id'), $msg );
				break;
			case 'saveEvent':
				$this->setRedirect( 'index.php?option=com_eventgallery&task=eventgallery&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );
				break;
		}
	}
    
	/**
	 * function to publish a single file/multiple files
	 * 
	 * @return unknown_type
	 */
	function Filepublish()
	{
		
		$model = $this->getModel('file');
		$model->publish(1);

		$file = & $model->getData();
		$msg = JText::_( 'COM_EVENTGALLERY_EVENT_FILE_UNPUBLISHED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&limitstart='.JRequest::getVar('limitstart').'&cid='.JRequest::getVar('folderid'), $msg );
	}
	
	/**
	 * function to unpublish a single file/multiple files
	 * 
	 * @return unknown_type
	 */	
	function Fileunpublish()
	{
		$model = $this->getModel('file');
		$model->publish(0);
		$file = & $model->getData();
		$msg = JText::_( 'COM_EVENTGALLERY_EVENT_FILE_PUBLISHED' );	
		$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&limitstart='.JRequest::getVar('limitstart').'&cid='.JRequest::getVar('folderid'), $msg );
	}
	
	/**
	 * function to allow Comments of a single file/multiple files
	 * 
	 * @return unknown_type
	 */
	function allowComments()
	{
		$model = $this->getModel('file');
		$model->allowComments(1);

		$file = & $model->getData();
		$msg = JText::_( 'COM_EVENTGALLERY_COMMENTS_ENABLE_FOR_FILE' );


		$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&limitstart='.JRequest::getVar('limitstart').'&cid='.JRequest::getVar('folderid'), $msg );
	}
	
	/**
	 * function to disallow Comments of a single file/multiple files
	 * 
	 * @return unknown_type
	 */	
	function disallowComments()
	{
		$model = $this->getModel('file');
		$model->allowComments(0);

		$file = & $model->getData();
		$msg = JText::_( 'COM_EVENTGALLERY_COMMENTS_DISABLE_FOR_FILE' );

		$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&limitstart='.JRequest::getVar('limitstart').'&cid='.JRequest::getVar('folderid'), $msg );
	}	
	
	/**
	 * function to publish a file. This is uses for links in emails
	 * 
	 * @return unknown_type
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
	 * @return unknown_type
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
	 * @return unknown_type
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
	 * @return unknown_type
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
	 * funtion to generate a token for uploading files via nonadmin-site
	 * @return unknown_type
	 */
	function getUploadToken()
	{

		$model = $this->getModel('token');	
		$view = $this->getView('getUploadToken','raw');
		$view->setModel($model, true);
		$view->display();
	}
	


	function uploadFileByAjax() {

		$user =& JFactory::getUser();

		$path = JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery';
		@mkdir($path, 0777);
		
		
		$folder = JRequest::getVar('folder');
		$folder=str_replace('..','',$folder);

		$path=$path.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR ;
		@mkdir($path, 0777);


		$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
		$fn = str_replace('..','',$fn);

		$uploadedFiles = Array();

		$ajaxMode = false;

		if ($fn) {

			// AJAX call
			$ajaxMode = true;
			file_put_contents(
				$path. $fn,
				file_get_contents('php://input')
			);
			#echo "$fn uploaded in folder $folder";
			echo '<img class="thumbnail" src="'.JURI::base().("../components/com_eventgallery/helpers/image.php?view=resizeimage&folder=".$folder."&file=".$fn."&option=com_eventgallery&width=100&height=50").'" />';
			array_push($uploadedFiles, $fn);

		}
		else {

			// form submit
			$files = $_FILES['fileselect'];

			foreach ($files['error'] as $id => $err) {
				if ($err == UPLOAD_ERR_OK) {
					$fn = $files['name'][$id];
					$fn = str_replace('..','',$fn);
					move_uploaded_file(
						$files['tmp_name'][$id],
						$path. $fn
					);
					array_push($uploadedFiles, $fn);
				}
			}

		}

		$db =& JFactory::getDBO();
		foreach($uploadedFiles as $uploadedFile) {
			if (file_exists($path.$uploadedFile)) {
			
				
				@list($width, $height, $type, $attr) = getimagesize($path.$uploadedFile);
				
				$query = "REPLACE into #__eventgallery_file set 
							folder=".$db->Quote($folder).", 
							file=".$db->Quote($uploadedFile).",
							width=".$db->Quote($width).",
							height=".$db->Quote($height).",
							userid=".$db->Quote($user->id);

				$db->setQuery($query);
				$db->query();			
			} 
		}

		if (!$ajaxMode) {
			$msg = JText::_( 'COM_EVENTGALLERY_EVENT_UPLOAD_COMPLETE' );
			$this->setRedirect( 'index.php?option=com_eventgallery&task=upload', $msg );
		}

	}
	/**
	 * function to provide the upload-View 
	 * @return unknown_type
	 */
	function upload()
	{
		JRequest::setVar('hidemainmenu', 1);
		$model = $this->getModel('event');	
		$view = $this->getView('upload','html');
		$view->setModel($model, true);
		$view->display();
	}
	
	/**
	 * function so remove every cache-entry
	 * @return unknown_type
	 */
	function clearCache()
	{
		
		$path=JPATH_BASE.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'com_eventgallery';
		rrmDir($path);
		$msg = JText::_( 'COM_EVENTGALLERY_CLEAR_CACHE_DONE' );
		$this->setRedirect( 'index.php?option=com_eventgallery', $msg );
	}
	
	function saveEventOrder()
	{		
		$model = $this->getModel('folder');
		$model->storeOrder();
		$msg = JText::_( 'COM_EVENTGALLERY_EVENTS_ORDER_STORED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );		
	}
	
	function orderEventUp()
	{
		$model = $this->getModel('folder');
		$model->move(1);
		$this->setRedirect( 'index.php?option=com_eventgallery&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );	
	}
	
	function orderEventDown()
	{
		$model =$this->getModel('folder');
		$model->move(-1);
		$this->setRedirect( 'index.php?option=com_eventgallery&folder='.JRequest::getVar('folder').'&file='.JRequest::getVar('file'), $msg );		
	}
	
	function saveFileOrder()
	{		
		$model = $this->getModel('file');
		$model->storeOrder();
		$msg = JText::_( 'COM_EVENTGALLERY_FILE_ORDER_STORED' );
		$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&cid='.JRequest::getVar('folderid'), $msg );		
	}
	
	function orderFileUp()
	{
		$model = $this->getModel('file');
		$model->move(1);
		
		$file = & $model->getData();
		
		$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&cid='.JRequest::getVar('folderid'), $msg );	
	}
	
	function orderFileDown()
	{
		$model =$this->getModel('file');
		
		$model->move(-1);
		
		$file = & $model->getData();
		
		#print_r($file);
		
		$this->setRedirect( 'index.php?option=com_eventgallery&task=edit&cid='.JRequest::getVar('folderid'), $msg );		
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
?>
