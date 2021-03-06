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

<a href="#" id="facebook-post-image"><img src="<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/facebook.png' ?>" alt="Facebook" title="Facebook"></a>
<script type="text/javascript">

	var shareFunction = function(e) {
		e.preventDefault();

		//change the facebook icon
		$$('#facebook-post-image img').set('src',"<?php echo JUri::base().'components/com_eventgallery/media/images/loading.gif' ?>");

		var wallPost = {
		    picture: "<?php echo $this->imageurl ?>"
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
	                $$('#facebook-post-image img').set('src',"<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/facebook.png' ?>");
	            });
	        } else {
	            //console.log('User cancelled login or did not fully authorize.');
	            $$('#facebook-post-image img').set('src',"<?php echo JUri::base().'components/com_eventgallery/media/images/social/32/facebook.png' ?>");
	        }
	    }, {scope: 'publish_stream'});

	};

	$('facebook-post-image').addEvent('click', shareFunction);

</script>