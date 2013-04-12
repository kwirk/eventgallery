<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 

?>

<h1><?php echo $this->event->folder?></h1> 
  

<form id="upload" action="<?php echo JRoute::_("index.php?option=com_eventgallery&task=uploadFileByAjax&folder=".$this->event->folder."&format=raw&",false); ?>" method="POST" enctype="multipart/form-data">  
<fieldset>  
	<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="30000000" />  
	<div>  
	    <label for="fileselect"><?php echo JText::_( 'COM_EVENTGALLERY_EVENT_UPLOAD_FILES_TO_UPLOAD' ); ?>:</label>  
	    <input type="file" id="fileselect" name="fileselect[]" multiple="multiple" />  
	    
	</div>  
	<div id="submitbutton">  
	    <button type="submit"><?php echo JText::_( 'COM_EVENTGALLERY_EVENT_UPLOAD_UPLOAD_FILES' ); ?></button>  
	</div>  
</fieldset>  
</form>  

<ul id="progress" class="thumbnails"></ul>


<form action="index.php" method="post" name="adminForm" id="adminForm">

<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="id" value="<?php echo $this->event->id; ?>" />
<input type="hidden" name="task" value="" />

</form>

<style>
	#progress p
	{
		display: block;
		width: 240px;
		padding: 2px 5px;
		margin: 2px 0;
		border: 1px inset #446;
		border-radius: 5px;
		background: #eee;
	}

	#progress p.success
	{
		background: #0c0 none 0 0 no-repeat;
	}

	#progress p.failed
	{
		background: #c00 none 0 0 no-repeat;
}
</style>

<script>

(function() {

/*
based on: 

filedrag.js - HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net

*/

	// file selection
	function FileSelectHandler(e) {

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;
		// process all File objects
		for (var i = 0, f; f = files[i]; i++) {
			UploadFile(f);
		}

	}


	// upload JPEG files
	function UploadFile(file) {

		var xhr = new XMLHttpRequest();
		if (xhr.upload && file.type == "image/jpeg" && file.size <= document.getElementById("MAX_FILE_SIZE").value) {

			// create progress bar
			var o = document.getElementById("progress");
			var progress = o.appendChild(document.createElement("li"));
			progress.appendChild(document.createTextNode("upload " + file.name));
			progress.addClass("uploading");


			// file received/failed
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState == 4) {
					progress.className = (xhr.status == 200 ? "success" : "failure");
					progress.innerHTML = xhr.responseText;
					progress.removeClass('uploading');
				}
			};

			// start upload
			xhr.open("POST", document.getElementById("upload").action+"?ajax=true&file="+file.name, true);
			//xhr.setRequestHeader("X_FILENAME", file.name);
			xhr.send(file);
			//console.log('file send.')

		} else {
			//console.log("invalid file, will not try to upload it");
		}

	}


	// initialize
	function Init() {

		var fileselect = document.getElementById("fileselect"),
			submitbutton = document.getElementById("submitbutton");

		// file select
		
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();

		if (xhr.upload) {

			// remove submit button
			submitbutton.style.display = "none";
		}
		//console.log('Init fileupload done.');

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}


})();
</script>