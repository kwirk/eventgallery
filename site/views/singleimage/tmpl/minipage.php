<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
?><html>
	<head>
		<title><?php 
			if (strlen($this->model->folder->description)>0) {
				echo $this->model->folder->description;
			} else {
				echo $this->model->file->folder;
			}

			echo ' - ';

			if (strlen($this->model->file->title)>0) {
				echo $this->model->file->title;
			} else {
				echo $this->model->file->file;
			}
		?></title>
	</head>
	<body>
		<a href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=event&folder='.$this->model->file->folder)?>">
		<img src="<?php echo  $this->model->file->getImageUrl(null, null, true) ?>">
		</a>
	</body>
</html>
		
		