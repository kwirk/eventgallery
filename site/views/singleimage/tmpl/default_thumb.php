<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<a href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->file->folder."&file=".$this->file->file) ?>#image">
<?php echo $this->file->getThumbImgTag(100,100, "", true); ?>
</a>