<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_eventgallery/helpers/tags.php';
require_once JPATH_SITE . '/components/com_eventgallery/helpers/route.php';
require_once JPATH_SITE . '/components/com_eventgallery/helpers/textsplitter.php';

/**
 * Eventgallery Search plugin
 *
 */
class PlgSearchEventgallery extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * @return array An array of search areas
     */
    public function onContentSearchAreas()
    {
        static $areas = array(
            'events' => 'PLG_EVENTGALLERY_SEARCH_EVENTS'
            #'event' => 'PLG_EVENTGALLERY_SEARCH_EVENT'
        );
        return $areas;
    }

    /**
     * Weblink Search method
     *
     * The sql must return the following fields that are used in a common display
     * routine: href, title, section, created, text, browsernav
     * @param string Target search string
     * @param string mathcing option, exact|any|all
     * @param string ordering option, newest|oldest|popular|alpha|category
     * @param mixed  An array if the search it to be restricted to areas, null if search all
     */
    public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
    {
        $db = JFactory::getDbo();
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());

        $searchText = $text;

        if (is_array($areas))
        {
            if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
            {
                return array();
            }
        }


        $limit = $this->params->def('search_limit', 50);



        $text = trim($text);
        if ($text == '')
        {
            return array();
        }
        $section = JText::_('PLG_EVENTGALLERY_SEARCH');

        $wheres = array();
        switch ($phrase)
        {
            case 'exact':
                $text = $db->quote('%' . $db->escape($text, true) . '%', false);
                $wheres2 = array();
                $wheres2[] = 'folder.folder LIKE ' . $text;
                $wheres2[] = 'folder.description LIKE ' . $text;
                $wheres2[] = 'folder.text LIKE ' . $text;
                $wheres2[] = 'folder.foldertags LIKE ' . $text;
                $where = '(' . implode(') OR (', $wheres2) . ')';
                break;

            case 'all':
            case 'any':
            default:
                $words = explode(' ', $text);
                $wheres = array();
                foreach ($words as $word)
                {
                    $word = $db->quote('%' . $db->escape($word, true) . '%', false);
                    $wheres2 = array();
                    $wheres2[] = 'folder.folder LIKE ' . $word;
                    $wheres2[] = 'folder.description LIKE ' . $word;
                    $wheres2[] = 'folder.text LIKE ' . $word;
                    $wheres2[] = 'folder.foldertags LIKE ' . $word;
                    $wheres[] = implode(' OR ', $wheres2);
                }
                $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                break;
        }

        switch ($ordering)
        {
            case 'oldest':
                $order = 'folder.date ASC';
                break;

            case 'newest':
                $order = 'folder.date DESC';
                break;

            default:
                $order = 'folder.position DESC';
        }

        $return = array();

        $query = $db->getQuery(true);

        $query->select('folder.description as title, folder.foldertags as foldertags, folder.folder as folder, folder.text as text, folder.date as created');
        $query->from('#__eventgallery_folder AS folder')
            ->where('(' . $where . ') AND folder.published=1')
            ->order($order);


        $db->setQuery($query, 0, $limit);
        $rows = $db->loadObjectList();

        $return = array();


        if ($rows)
        {

            foreach ($rows as $key => $row)
            {

                $splittedText = EventgalleryHelpersTextsplitter::split($rows[$key]->text);

                $rows[$key]->text=$splittedText->introtext;
                $rows[$key]->href = EventgalleryHelpersRoute::createEventRoute($rows[$key]->folder, $rows[$key]->foldertags);
                $rows[$key]->section="";
                $rows[$key]->browsernav="";

                $return[] = $rows[$key];
            }

        }

        return $return;
    }


}
