<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controllerform' );

class EventgalleryControllerUpload extends JControllerForm
{

    protected $default_view = 'upload';

	public function getModel($name = 'Event', $prefix ='EventgalleryModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

	/**
	 * function to provide the upload-View 
	 */
	function upload()
	{
        $this->display();
	}


	function uploadFileByAjax() {

		$user = JFactory::getUser();

		$path = JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'eventgallery';
		@mkdir($path);
		
		
		$folder = JRequest::getString('folder');
		$folder=JFile::makeSafe($folder);
		

		$path=$path.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR ;
		@mkdir($path);


		$fn = JRequest::getString('file', false);
		$fn=JFile::makeSafe($fn);

		$uploadedFiles = Array();

		$ajaxMode = JRequest::getString('ajax',false);
		echo $fn." done";

		if ($fn) {

			// AJAX call
			file_put_contents(
				$path. $fn,
				file_get_contents('php://input')
			);
			#echo "$fn uploaded in folder $folder";
			echo '<img alt="Done '.$fn.'" class="thumbnail" src="'.JURI::base().("../components/com_eventgallery/helpers/image.php?view=resizeimage&folder=".$folder."&file=".$fn."&option=com_eventgallery&width=100&height=50").'" />';
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

		$db = JFactory::getDBO();
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
	
	public function cancel() {
		$this->setRedirect( 'index.php?option=com_eventgallery');
	}
}
