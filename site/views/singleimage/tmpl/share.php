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

$title = "";
$description = $this->model->folder->description;;
$subject = $this->model->folder->description; 
$link =  JRoute::_('index.php?option=com_eventgallery&view=singleimage&format=raw&folder='.$this->model->file->folder.'&file='.$this->model->file->file, true, -1);
$image = $this->model->file->getImageUrl(500,500);
$twitter = $description;

?>
		
<a href="#" style="float: left" class="social-share-button" rel="sharingbutton-close"><i class="big"></i></a>	 

<a href="#" onclick="javascript:FB.ui({
					  method: 'feed',
					  link: '<?php echo $link ?>'
					}, function(response){}); return false;" 
	><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/facebook.png' ?>" alt="Facebook" title="Facebook"></a>

<a href="https://plus.google.com/share?url=<?php echo urlencode($link)?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');return false;"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/google.png' ?>" alt="Google+" title="Google+"></a>


<a href="https://twitter.com/intent/tweet?source=webclient&text=<?php echo $twitter?>" 
   onclick="window.open('http://twitter.com/share?url=<?php echo $link?>&text=<?php echo urlencode($twitter)?>', 'twitterwindow', 'height=450, width=550, toolbar=0, location=1, menubar=0, directories=0, scrollbars=auto'); return false;"
   ><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/twitter.png' ?>" alt="Twitter" title="Twitter"></a>

<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($link)?>&media=<?php echo urlencode($image)?>&description=<?php echo $description?>"
	onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');return false;"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/interest.png' ?>" alt="Pinterest" title="Pinterest"></Twitter>

<a href="mailto:?subject=<?php echo $subject?>&body=<?php echo $link?>" onclick=""> <img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/email.png' ?>" alt="Mail" title="Mail"></a>
		
		