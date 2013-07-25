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
		
<div class="addthis_toolbox addthis_default_style addthis_32x32_style" 
		addthis:description="foobar text foo" 
		addthis:title="<?php echo $this->escape($this->model->file->getPlainTextTitle()).' - '.$this->model->file->file ?>" 
		addthis:url="<?php echo JRoute::_('index.php?option=com_eventgallery&view=singleimage&format=raw&folder='.$this->model->file->folder.'&file='.$this->model->file->file, true, -1) ?>"
	 >
<a href="#" style="float: left" class="social-share-button" rel="sharingbutton-close"><i class="big"></i></a>	 
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_pinterest_pinit" pi:pinit:media="<?php echo  rawurlencode($this->model->file->getImageUrl(null, null, true)) ?>">        </a> 
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>

<script type="text/javascript">
	addthis.toolbox('.addthis_toolbox');
</script>


		
		