<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<style>
    .mod-eventgallery-event .thumbnail{
        float: left;
    }

</style>

<div class="mod-eventgallery-event">
    <div class="thumbnails">
        <?php foreach($files as $file):?>

                <a class="thumbnail" href="<?php echo JRoute::_(EventgalleryHelpersRoute::createEventRoute($folder->getFolderName(), $folder->getFolderTags())) ?>"><?php
                    /**
                     * @var EventgalleryHelpersImageInterface $file
                     */
                    echo $file->getThumbImgTag($params->get('thumb_width'), $params->get('thumb_width'), '', true);
                ?></a>

        <?php endforeach; ?>
        <div style="clear:both"></div>
    </div>
</div>