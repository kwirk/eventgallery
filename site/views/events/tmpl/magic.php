<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * @var JCacheControllerCallback $cache
 */
?>




<?php  echo  $this->loadSnippet('social'); ?>

<p class="greetings"><?php echo $this->params->get('greetings'); ?></p>

<?php  echo $this->loadSnippet('event/'.str_replace('_:','',$this->params->get('event_layout', 'default'))); ?>

<?php echo $this->loadSnippet('footer_disclaimer'); ?>
