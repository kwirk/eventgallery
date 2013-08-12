<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access'); ?>




<?php  echo  $this->loadSnippet('cart'); ?>
<?php  echo  $this->loadSnippet('social'); ?>


<?php IF ($this->folder->cartable == 1 && $this->params->get('use_cart', '1')==1): ?>
    <?php echo $this->loadSnippet('imageset/imagesetselectionajax'); ?>
<?php ENDIF ?>

<?php  echo $this->loadSnippet('event/ajaxpaging'); ?>

<?php echo $this->loadSnippet('footer_disclaimer'); ?>
