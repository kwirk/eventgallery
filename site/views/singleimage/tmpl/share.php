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
$subject = $this->model->folder->description." "; 
$link =  JRoute::_('index.php?option=com_eventgallery&view=singleimage&format=raw&folder='.$this->model->file->folder.'&file='.$this->model->file->file, true, -1);
$image = $this->model->file->getImageUrl(500,500, false);
$twitter = $description;

$imageurl = JURI::base().'images/eventgallery/'.$this->model->file->folder.'/'.$this->model->file->file;
// handle picasa images
if (strpos($this->model->file->folder,'@')>0) {
	$imageurl = $this->model->file->getImageUrl(600, 600, true);
}

?>
<?php IF ($this->params->get('use_social_sharing_button', 0)==1):?>			    		
<a href="#" style="float: left" class="social-share-button" rel="sharingbutton-close"><i class="big"></i></a>	 

	<?php IF ($this->params->get('use_social_sharing_facebook', 0)==1):?>			    
		<a href="#" id="facebook-post-image"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/facebook.png' ?>" alt="Facebook" title="Facebook"></a>
		<script>

		var shareFunction = function(e) {
			e.preventDefault();

			var wallPost = {
			    picture: "<?php echo $imageurl ?>"
			};

			FB.login(function(response) {
		        if (response.authResponse) {
		            var access_token =   FB.getAuthResponse()['accessToken'];
		            FB.api('/me/photos?access_token='+access_token, 'post', { url: wallPost.picture, access_token: access_token }, function(response) {
		                if (!response || response.error) {
		                    //alert('Error occured: ' + JSON.stringify(response.error));
		                  } else {
		                    alert('<?php echo JTEXT::_('COM_EVENTGALLERY_SOCIAL_SHARE_IMAGE_SHARED')?>');
		                  }
		            });
		        } else {
		            //console.log('User cancelled login or did not fully authorize.');
		        }
		    }, {scope: 'publish_stream'});

		};

		$('facebook-post-image').addEvent('click', shareFunction);

		</script>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_google', 0)==1):?>			    
		<a href="https://plus.google.com/share?url=<?php echo urlencode($link)?>" onclick="javascript:window.open(this.href,
		  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');return false;"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/google.png' ?>" alt="Google+" title="Google+"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_twitter', 0)==1):?>			    
		<a href="https://twitter.com/intent/tweet?source=webclient&text=<?php echo $twitter?>" 
		   onclick="window.open('http://twitter.com/share?url=<?php echo $link?>&text=<?php echo urlencode($twitter)?>', 'twitterwindow', 'height=450, width=550, toolbar=0, location=1, menubar=0, directories=0, scrollbars=auto'); return false;"
		   ><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/twitter.png' ?>" alt="Twitter" title="Twitter"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_pinterest', 0)==1):?>			    
		<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($link)?>&media=<?php echo urlencode($image)?>&description=<?php echo $description?>"
			onclick="javascript:window.open(this.href,
		  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes');return false;"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/interest.png' ?>" alt="Pinterest" title="Pinterest"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_email', 0)==1):?>			    
		<a href="mailto:?subject=<?php echo $subject?>&body=<?php echo $link?>" onclick=""> <img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/email.png' ?>" alt="Mail" title="Mail"></a>
	<?php ENDIF ?>

	<?php IF ($this->params->get('use_social_sharing_download', 0)==1):?>			    
		<a href="<?php echo  JURI::base().'images/eventgallery/'.$this->model->file->folder.'/'.$this->model->file->file ?>" target="_blank" lt="Download" title="Download"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/icons/32x32/download-icon.png' ?>" alt="Download" title="Download"</a>
	<?php ENDIF ?>


<?php ENDIF ?>