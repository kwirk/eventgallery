<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_eventgallery/router.php';

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
                $wheres2[] = 'folder.tags LIKE ' . $text;
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
                    $wheres2[] = 'folder.tags LIKE ' . $word;
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

        $query->select('folder.description as title, folder.tags as tags, folder.folder as folder, folder.text as text, folder.date as created');
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

                // get the full text part
                $initialtext = $row->text;
                $introtext = "";
                $fulltext = "";

                $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
                $tagPos = preg_match($pattern, $initialtext);

                if ($tagPos == 0)
                {
                    $introtext = $initialtext;
                    $fulltext = $initialtext;
                }
                else
                {
                    list ($introtext, $fulltext) = preg_split($pattern, $initialtext, 2);
                }

                $rows[$key]->text=$introtext;
                $rows[$key]->href = $this->createEventRoute($rows[$key]->folder, $rows[$key]->tags);
                $rows[$key]->section="";
                $rows[$key]->browsernav="";

                $return[] = $rows[$key];
            }

        }

        return $return;
    }

    function createEventRoute($foldername, $tags) {


        $app		= JFactory::getApplication();
        $menus		= $app->getMenu('site');
        /**
         * @var JLanguage $lang
         */
        $lang = JFactory::getLanguage();
        $language = $lang->getTag();


        $component	= JComponentHelper::getComponent('com_eventgallery');

        $attributes = array('component_id');
        $values = array($component->id);

        // take the current lang into account
        $attributes[] = 'language';
        $values[] = array($language, '*');


        $items		= $menus->getItems($attributes, $values);
        $itemid = NULL;

        foreach ($items as $item)
        {
            if (isset($item->query) && isset($item->query['view']))
            {
                $view = $item->query['view'];

                if ($view == 'events') {
                    if (strlen($item->params->get('tags',''))==0) {
                        $itemid = $item->id;
                    } else {
                        if ( $this->checkTags($item->params->get('tags'), $tags) ) {
                            $itemid = $item->id;
                        }
                    }
                }

                if ($view == 'event' && isset($view->query['folder']) && $view->query['folder']==$foldername) {
                    $itemid = $item->id;
                }
            }

            if ($itemid != NULL) {
                break;
            }
        }

        // if not found, return language specific home link
        if ($itemid==NULL) {
            $default = $menus->getDefault($language);
            $itemid =  !empty($default->id) ? $default->id : null;
        }

        $url = JRoute::_('index.php?option=com_eventgallery&view=event&folder='.$foldername.'&Itemid='.$itemid);

        return $url;
    }

    /**
     * Checks if at least one needle tag is part of the tags string.
     * Returns false if the needle is empty
     *
     *
     * @param $tags   a comma or space separated string (foo, bar, foo2)
     * @param $needleTags a comma or space separated string (foo, bar, foo2)
     * @return bool returns true if one element in the needeTags is contained in the tags
     */
    function checkTags($tags, $needleTags) {
        if (strlen($needleTags)==0) {
            return false;
        }


        // handle space and comma separated lists like "foo bar" or "foo, bar"

        $tempTags = explode(',',str_replace(" ", ",", $needleTags));
        array_walk($tempTags, 'trim');

        $needleTags = Array();

        foreach($tempTags as $tag)
        {
            if(strlen($tag)>0)
            {
                $needleTags[] = $tag;
            }
        }

        $regex = "/(".implode($needleTags,'|').")/i";

        if (preg_match($regex, $tags)) {
            return true;
        }



        // no match
        return false;

    }
}
