<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.joomla help
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.utilities.utility');

require_once JPATH_ADMINISTRATOR.'/components/com_eventgallery/helpers/helppagebuilder.php';

/*
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.pagebreak
 * @since       1.6
 */
class PlgContentEventgallery_help_toc extends JPlugin
{

	/**
	 * @param   string	The context of the content being passed to the plugin.
	 * @param   object	The article object.  Note $article->text is also available
	 * @param   object	The article params
	 * @param   integer  The 'page' number
	 *
	 * @return  void
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{



		$canProceed = $context == 'com_content.article';
		if (!$canProceed)
		{
			return;
		}

		$helper = new EventgalleryHelpersHelppagebuilder();
	    $row->text = $helper->insertToc($row->text);

	    $row->text .= '
        <script type="text/javascript">
	        jQuery(document).ready(function($) {
	  
			
				var url = window.location.href;
				var index = url.indexOf(\'#\');
				
				if (index > 0) {
				  url = url.substring(0, index);
				}

	        	$.each(
	        		 $(\'a[href^="#"]\'),
	        		 function(i, item){
	        		 	var hashPos = item.href.indexOf(\'#\')	        		 	
	        			item.href=url+item.href.substr(hashPos);	        		
	        		}.bind(this)	
	        	);
			});
        </script>
		';



		return true;
	}




	 

}
