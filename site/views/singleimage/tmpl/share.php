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

$this->description = $this->model->folder->getDescription();
$this->subject = $this->model->folder->getDescription()." "; 
$this->link =  JRoute::_('index.php?option=com_eventgallery&view=singleimage&format=raw&folder='.$this->model->file->getFolderName().'&file='.$this->model->file->getFileName(), true, -1);
$this->image = $this->model->file->getImageUrl(500,500, false);
$this->twitter = $this->description;

$this->imageurl = JURI::base().'images/eventgallery/'.$this->model->file->getFolderName().'/'.$this->model->file->getFileName();
// handle picasa images

$this->imageurl = $this->model->file->getOriginalImageUrl();



?>
<?php IF ($this->params->get('use_social_sharing_button', 0)==1 && $this->model->folder->getAttribs()->get('use_social_sharing',1)==1):?>			    		
<a href="#" style="float: left" class="social-share-button" rel="sharingbutton-close"><i class="big"></i></a>	 

	<?php IF ($this->params->get('use_social_sharing_facebook', 0)==1 && $this->model->folder->getAttribs()->get('use_social_sharing_facebook',1)==1):?>	

		<?php IF ($this->params->get('use_social_sharing_facebook_type', 'photo_share') == 'photo_share'): ?>		    
			<?php echo $this->loadTemplate('facebook_photoshare'); ?>
		<?php ENDIF ?>

		<?php IF ($this->params->get('use_social_sharing_facebook_type', 'photo_share') == 'feed_dialog'): ?>		    
			<?php echo $this->loadTemplate('facebook_feeddialog'); ?>
		<?php ENDIF ?>

		<?php IF ($this->params->get('use_social_sharing_facebook_type', 'photo_share') == 'share_dialog'): ?>		    
			<?php echo $this->loadTemplate('facebook_sharedialog'); ?>
		<?php ENDIF ?>

	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_google', 0)==1 && $this->model->folder->getAttribs()->get('use_social_sharing_google',1)==1):?>			    
		<a href="https://plus.google.com/share?url=<?php echo urlencode($this->link)?>" onclick="javascript:window.open(this.href,
		  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');return false;"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/google.png' ?>" alt="Google+" title="Google+"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_twitter', 0)==1 && $this->model->folder->getAttribs()->get('use_social_sharing_twitter',1)==1):?>			    
		<a href="https://twitter.com/intent/tweet?source=webclient&text=<?php echo $this->twitter?>" 
		   onclick="window.open('http://twitter.com/share?url=<?php echo $this->link?>&text=<?php echo urlencode($this->twitter)?>', 'twitterwindow', 'height=450, width=550, toolbar=0, location=1, menubar=0, directories=0, scrollbars=auto'); return false;"
		   ><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/twitter.png' ?>" alt="Twitter" title="Twitter"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_pinterest', 0)==1 && $this->model->folder->getAttribs()->get('use_social_sharing_pinterest', 1)==1):?>			    
		<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($this->link)?>&media=<?php echo urlencode($this->image)?>&description=<?php echo $this->description?>"
			onclick="javascript:window.open(this.href,
		  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');return false;"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/interest.png' ?>" alt="Pinterest" title="Pinterest"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_email', 0)==1 && $this->model->folder->getAttribs()->get('use_social_sharing_email', 1)==1):?>			    
		<a href="mailto:?subject=<?php echo $this->subject?>&body=<?php echo $this->link?>" onclick=""> <img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/email.png' ?>" alt="Mail" title="Mail"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_download', 0)==1 && $this->model->folder->getAttribs()->get('use_social_sharing_download', 1)==1):?>			    
		<a href="<?php echo $this->imageurl; ?>" target="_blank" lt="Download" title="Download"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/icons/32x32/download-icon.png' ?>" alt="Download" title="Download"</a>
	<?php ENDIF ?>


<?php ENDIF ?>