<?php // no direct access

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');


?>

<style type="text/css">
    .track-my-order .form,
    .track-my-order .signin
    {
        float: left;
        width:50%;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        padding: 20px;
        min-width: 350px;
    }

    .track-my-order .signin {
        min-width: 250px;
    }

    .track-my-order .desc {
        margin-bottom: 20px;
    }


</style>
<div class="track-my-order">
    <div class="form">
      <?php echo $this->loadTemplate('trackingform')?>
    </div>

    <div class="signin">
        <?php echo $this->loadTemplate('signinform')?>
    </div>
</div>
<div style="clear: both"></div>