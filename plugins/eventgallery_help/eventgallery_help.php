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
class PlgContentEventgallery_help extends JPlugin
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

		$document = JFactory::getDocument();	
	
	    $css=JURI::base().'administrator/components/com_eventgallery/media/css/manual.css';
		$document->addStyleSheet($css);	

		$found = preg_match_all("/{markdown:.*}/", $row->text, $matches);
		if (!$found) {
			return true;
		}

		
		foreach($matches[0] as $match) {

			$file = substr($match, 10, strlen($match)-11);
			

			if (substr($file, strlen($file)-3)!='.md') {
				break;			 
			} 


	        $helper = new EventgalleryHelpersHelppagebuilder();
	        $my_html = $helper->process($file);

			
			$row->text = str_replace($match, $my_html, $row->text);			 

		}


		return true;
	}




	

}
