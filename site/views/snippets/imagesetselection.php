<?php // no direct access
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access'); ?>

<style type="text/css">
    .imagetypeselection-container {
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>

<div class="imagetypeselection-container">
<button class="btn btn-primary imagetypeselection-show"><?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGES') ?></button>

<div class="well imagetypeselection" style="display:none">
    <?php include dirname(__FILE__).'/imagesetinformation.php'; ?>
    <div class="btn-group pull-right">
        <button class="btn eventgallery-add-all"><?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGES_ADD_ALL') ?></button>
        <button class="btn imagetypeselection-hide"><?php echo JText::_('COM_EVENTGALLERY_PRODUCT_BUY_IMAGES_CLOSE') ?></button>       
    </div>
    <div class="clearfix"></div>
</div>
</div>

<script>
    
    window.addEvent("domready", function () {
    
        var imagetypeselection = $$('.imagetypeselection')[0];
        var imagetypeselectionShowButton = $$('.imagetypeselection-show')[0];
        

        var imagetypeselectionFX = new Fx.Slide(imagetypeselection);
        var imagetypeselectionShowButtonFX = new Fx.Slide(imagetypeselectionShowButton);


        function closeImageTypeSelection(e) {
            e.preventDefault();
            imagetypeselectionFX.slideOut();
            imagetypeselectionShowButtonFX.slideIn();     
            $$(".eventgallery-add2cart").hide();
        }

        function openImageTypeSelection(e) {
            e.preventDefault();
            imagetypeselectionFX.slideIn();
            imagetypeselectionShowButtonFX.slideOut();

            $$(".eventgallery-add2cart").show();
        }

        $$('.imagetypeselection-hide').addEvent('click', closeImageTypeSelection);
        
        $$('.imagetypeselection-show').addEvent('click', openImageTypeSelection); 

        
       

        imagetypeselectionFX.slideOut().chain(function() {imagetypeselection.show()} );
        
        $$('.imagetypeselection-show').show();


        $$(".eventgallery-add2cart").hide();
    
    });

</script>
