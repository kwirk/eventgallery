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
jimport( 'joomla.html.pagination');
jimport( 'joomla.html.html');

/** @noinspection PhpUndefinedClassInspection */
class EventgalleryViewComment extends EventgalleryLibraryCommonView
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		EventgalleryHelpersEventgallery::addSubmenu('comment');      
        $this->sidebar = JHtmlSidebar::render();
		return parent::display($tpl);
	}

	private function addToolbar() {
		$isNew		= ($this->item->id < 1);
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_COMMENT' ).': <small><small>[ ' . $text.' ]</small></small>' );
			
		JToolBarHelper::apply('comment.apply');			
		JToolBarHelper::save('comment.save');
		if ($isNew)  {			
			JToolBarHelper::cancel( 'comment.cancel' );
		} else {
			JToolBarHelper::cancel( 'comment.cancel', JText::_( 'JTOOLBAR_CLOSE' ) );
		}


	}

}