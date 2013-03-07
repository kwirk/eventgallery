<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access'); 

?>

<form method="POST" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value="" />
</form>

<?php
	 include_once JPATH_COMPONENT.'/helpers/php_markdown_extra/markdown.php';



	 $my_html = Markdown(file_get_contents(JPATH_COMPONENT."/doc/readme.md"));

	 //fix links
	 $search  = '<img src="img/';
	 $replace = '<img src="'.JURI::base().'components/com_eventgallery/doc/img/';
	 $my_html = str_replace($search, $replace, $my_html);
	


	 echo $my_html;
?>

