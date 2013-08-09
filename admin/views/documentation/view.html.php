<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;



class EventgalleryViewDocumentation extends EventgalleryLibraryCommonView
{

    protected $content;

	function display($tpl = null)
	{

        $filename = 'intro';

        if ($this->getLayout() != 'default') {
            $filename = JFile::makeSafe($this->getLayout());
        }

        $this->setLayout('default');

        $file = "administrator/components/com_eventgallery/doc/$filename.md";


        $helper = new EventgalleryHelpersHelppagebuilder();
        $content = $helper->process($file);
        $this->content = $helper->insertToc($content);

		EventgalleryHelpersEventgallery::addSubmenu('documentation');		
		$this->sidebar = JHtmlSidebar::render();
		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar() {
		JToolBarHelper::title(   JText::_( 'COM_EVENTGALLERY_SUBMENU_DOCUMENTATION' ) );
        JToolBarHelper::cancel( 'documentation.cancel', 'Close' );
	}



}

