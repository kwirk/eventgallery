<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.pathway');
jimport( 'joomla.html.pagination');


class EventgalleryViewPassword extends JViewLegacy
{
	function display($tpl = null)
	{		
	  
		$app	 = JFactory::getApplication();
		$document = JFactory::getDocument();
		$params	 = $app->getParams();	
		
		$file = JRequest::getString('file','');
		$folder = JRequest::getString('folder','');

		$model = JModelLegacy::getInstance('Event', 'EventgalleryModel');
		
		$folder = $model->getFolder($folder);

		if (!is_object($folder)) {
			$app->redirect(JRoute::_("index.php?", false));
		}

		$formAction = JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$folder->folder);

		$this->assign('folder', $folder);
		$this->assign('file', $file);
		$this->assign('params', $params);
		$this->assignRef('formaction', $formAction);
		$this->_prepareDocument();
		
		parent::display($tpl);
	}
	
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$pathway = $app->getPathway();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		

		$title = $this->params->get('page_title', '');

		if ($this->folder->description) {
			$title = $this->folder->description;
		}


		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		if (empty($title)) {
			$title = $this->folder->description;
		}
		$this->document->setTitle($title);

		if ($this->folder->text)
		{
			$this->document->setDescription($this->folder->text);
		}
		elseif (!$this->folder->text && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
	}
}

?>
