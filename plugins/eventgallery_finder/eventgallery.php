<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


defined('JPATH_BASE') or die;

// Load the base adapter.
require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

/**
 * Finder adapter for Event Gallery
 */
class PlgFinderEventgallery extends FinderIndexerAdapter
{
    /**
     * The plugin identifier.
     *
     * @var    string
     * @since  2.5
     */
    protected $context = 'Eventgallery';

    /**
     * The extension name.
     *
     * @var    string
     * @since  2.5
     */
    protected $extension = 'com_eventgallery';

    /**
     * The sublayout to use when rendering the results.
     *
     * @var    string
     * @since  2.5
     */
    protected $layout = 'event';

    /**
     * The type of content that the adapter indexes.
     *
     * @var    string
     * @since  2.5
     */
    protected $type_title = 'Event';

    /**
     * The table name.
     *
     * @var    string
     * @since  2.5
     */
    protected $table = '#__eventgallery_folder';

    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * Method to update the item link information when the item category is
     * changed. This is fired when the item category is published or unpublished
     * from the list view.
     *
     * @param   string   $extension  The extension whose category has been updated.
     * @param   array    $pks        A list of primary key ids of the content that has changed state.
     * @param   integer  $value      The value of the state that the content has been changed to.
     *
     * @return  void
     *
     * @since   2.5
     */
    public function onFinderCategoryChangeState($extension, $pks, $value)
    {
        // Make sure we're handling com_eventgallery categories
        if ($extension == 'com_eventgallery')
        {
            $this->categoryStateChange($pks, $value);
        }
    }

    /**
     * Method to remove the link information for items that have been deleted.
     *
     * @param   string  $context  The context of the action being performed.
     * @param   JTable  $table    A JTable object containing the record to be deleted
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    public function onFinderAfterDelete($context, $table)
    {
        if ($context == 'com_eventgallery.event')
        {
            $id = $table->id;
        }
        elseif ($context == 'com_finder.index')
        {
            $id = $table->link_id;
        }
        else
        {
            return true;
        }
        // Remove the items.
        return $this->remove($id);
    }

    /**
     * Method to determine if the access level of an item changed.
     *
     * @param   string   $context  The context of the content passed to the plugin.
     * @param   JTable   $row      A JTable object
     * @param   boolean  $isNew    If the content has just been created
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    public function onFinderAfterSave($context, $row, $isNew)
    {
        // We only want to handle web links here. We need to handle front end and back end editing.
        if ($context == 'com_eventgallery.event' || $context == 'com_eventgallery.events')
        {
            // Check if the access levels are different
            if (!$isNew && $this->old_access != $row->access)
            {
                // Process the change.
                $this->remove($row->id);
            }

            // Reindex the item
            $this->reindex($row->id);
        }

        return true;
    }

    /**
     * Method to reindex the link information for an item that has been saved.
     * This event is fired before the data is actually saved so we are going
     * to queue the item to be indexed later.
     *
     * @param   string   $context  The context of the content passed to the plugin.
     * @param   JTable   $row     A JTable object
     * @param   boolean  $isNew    If the content is just about to be created
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    public function onFinderBeforeSave($context, $row, $isNew)
    {
        // We only want to handle web links here
        if ($context == 'com_eventgallery.event' || $context == 'com_eventgallery.events')
        {
            // Query the database for the old access level if the item isn't new
            $this->remove($row->id);
            $this->reindex($row->id);

        }

        return true;
    }

    /**
     * Method to update the link information for items that have been changed
     * from outside the edit screen. This is fired when the item is published,
     * unpublished, archived, or unarchived from the list view.
     *
     * @param   string   $context  The context for the content passed to the plugin.
     * @param   array    $pks      A list of primary key ids of the content that has changed state.
     * @param   integer  $value    The value of the state that the content has been changed to.
     *
     * @return  void
     *
     * @since   2.5
     */
    public function onFinderChangeState($context, $pks, $value)
    {
        // We only want to handle web links here
        if ($context == 'com_eventgallery.event' || $context == 'com_eventgallery.events')
        {
            foreach ($pks as $pk)
            {
                // Reindex the item
                $this->remove($pk);
                $this->reindex($pk);
            }
        }
        // Handle when the plugin is disabled
        if ($context == 'com_plugins.plugin' && $value === 0)
        {
            $this->pluginDisable($pks);
        }
    }

    protected function index(FinderIndexerResult $item, $format = 'html') {
        if (!JLanguageMultilang::isEnabled()) {
            $this->indexLanguage($item, $format, '');
            return;
        }

        $languages = JLanguageHelper::getLanguages();
        foreach ($languages as $lang) {
            $this->indexLanguage($item, $format, $lang->lang_code);
        }
    }

    /**
     * Method to index an item. The item must be a FinderIndexerResult object.
     *
     * @param   FinderIndexerResult  $item    The item to index as an FinderIndexerResult object.
     * @param   string               $format  The item format
     *
     * @return  void
     *
     * @since   2.5
     * @throws  Exception on database error.
     */
    protected function indexLanguage(FinderIndexerResult $item, $format = 'html', $language)
    {


        // Check if the extension is enabled
        if (JComponentHelper::isEnabled($this->extension) == false)
        {
            return;
        }

        if ($language=='') {
            $item->setLanguage();
        } else {
            $this->language = $language;
        }

        // Initialize the item parameters.
        $registry = new JRegistry;
        $registry->loadString($item->params);
        $item->params = $registry;

        $registry = new JRegistry;
        $registry->loadString($item->metadata);
        $item->metadata = $registry;

        // Build the necessary route and path information.
        $item->url = $this->getURL($item->id, $this->extension, $this->layout);
        $item->route = EventgalleryHelpersRoute::createEventRoute($item->folder, $item->foldertags);
        $item->path = FinderIndexerHelper::getContentPath($item->route);

        $item->title = $item->title;
        $splittedText = EventgalleryHelpersTextsplitter::split($item->text);
        $item->fulltext = $splittedText->fulltext;
        $item->introtext = $splittedText->introtext;
        $item->summary = $splittedText->introtext;
        $item->state = 1;
        $item->publish_start_date = 0;
        $item->publish_end_date = null;
        $item->access = $item->published;

        /*
         * Add the meta-data processing instructions based on the newsfeeds
         * configuration parameters.
         */
        // Add the meta-author.
        $item->metaauthor = $item->metadata->get('author');

        // Handle the link to the meta-data.
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'foldertags');
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'description');
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'fulltext');
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'introtext');

        // Add the type taxonomy data.
        $item->addTaxonomy('Type', 'Event');


        // Get content extras.
        FinderIndexerHelper::getContentExtras($item);

        // Index the item.
        $this->indexer->index($item);
    }


    /**
     * Method to setup the indexer to be run.
     *
     * @return  boolean  True on success.
     *
     * @since   2.5
     */
    protected function setup()
    {
        // Load dependent classes.
        require_once JPATH_SITE . '/components/com_eventgallery/helpers/tags.php';
        require_once JPATH_SITE . '/components/com_eventgallery/helpers/route.php';
        require_once JPATH_SITE . '/components/com_eventgallery/helpers/textsplitter.php';

        return true;
    }

    /**
     * Method to get the SQL query used to retrieve the list of content items.
     *
     * @param   mixed  $query  A JDatabaseQuery object or null.
     *
     * @return  JDatabaseQuery  A database object.
     *
     * @since   2.5
     */
    protected function getListQuery($query = null)
    {
        $db = JFactory::getDbo();
        // Check if we can use the supplied SQL query.
        $query = $query instanceof JDatabaseQuery ? $query : $db->getQuery(true)
            ->select('a.id as id,
                        a.folder as folder,
                        a.description as title,
                        a.published as published,
                        a.foldertags as foldertags,
                        a.text as text,
                        a.date as date,
                        a.lastmodified as lastmodified')
            ->from('#__eventgallery_folder AS a');

        return $query;
    }

    /**
     * Method to get the query clause for getting items to update by time.
     *
     * @param   string  $time  The modified timestamp.
     *
     * @return  JDatabaseQuery  A database object.
     *
     * @since   2.5
     */
    protected function getUpdateQueryByTime($time)
    {
        // Build an SQL query based on the modified time.
        $query = $this->db->getQuery(true)
            ->where('a.lastmodified >= ' . $this->db->quote($time));

        return $query;
    }



}
