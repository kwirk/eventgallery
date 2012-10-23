<?php defined('_JEXEC') or die('Restricted access'); ?>

<h1><?php echo $this->event->folder?></h1> 


<div>
<?php
					
	$helper = new MultiBitShiftHelper();
	$path=JURI::base()."components/com_eventgallery/media/";
	
	
	$uploaded_files = Array();
	foreach ($this->files as $file)
	{
		$uploaded_files[$file->file]=0;
	}
	
	$session =& JFactory::getSession();
	$sid = $session->getId();
	
	echo $helper->multi_bit_shift_field("files", "files1", array(
		'flash_path' =>  $path.'flash/',
		'flashCSS' => $path.'flash/css/siu.swf',
		'uploadURL' => JURI::base().JRoute::_("../index.php?option=com_eventgallery&task=uploadFile&folder=".$this->event->folder."&format=raw&",false),
		'removeFileURL' => JRoute::_("index.php?option=com_eventgallery&task=removeFile&folder=".$this->event->folder."&format=raw&",false),
		'tokenRequestURL' => JRoute::_("index.php?option=com_eventgallery&task=getUploadToken&folder=".$this->event->folder."&format=raw&",false),	
		'validation_object' => (new ValidateMbsFile("files")),
		'uploaded_files_array'=> $uploaded_files
	));
	
	
					
?>
</div>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<input type="hidden" name="option" value="com_eventgallery" />
<input type="hidden" name="id" value="<?php echo $this->event->id; ?>" />
<input type="hidden" name="task" value="" />

</form>