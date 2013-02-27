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
?>
<a href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->file->folder."&file=".$this->file->file) ?>#image">
<?php echo $this->file->getThumbImgTag(100,100, "", true); ?>
</a>