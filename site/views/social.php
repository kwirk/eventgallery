<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access'); ?>

<?php IF ($this->params->get('use_social_sharing_button', 0)==1):?>			    
	<script type="text/javascript">
		var addthis_config = {
			"data_track_addressbar":false
		};
	</script>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $this->params->get('social_sharing_addthis_pubid') ?>"></script>
<?php ENDIF ?>