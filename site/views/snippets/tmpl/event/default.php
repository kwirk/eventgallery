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
?>


<script type="text/javascript">

    var eventgalleryImageList;
    var eventgalleryLazyloader;

    window.addEvent("domready", function () {
        var options = {
            rowHeight: <?php echo $this->params->get('event_default_list_thumbnail_height',100); ?>,
            rowHeightJitter: <?php echo $this->params->get('event_default_list_thumbnail_jitter', 0); ?>,
            firstImageRowHeight: <?php echo $this->params->get('event_default_list_thumbnail_first_item_height',1); ?>,
            eventgallerySelector: '.eventgallery-thumbnails',
            eventgalleryImageSelector: '.eventgallery-thumbnails .thumbnail',
            initComplete: function () {
                eventgalleryLazyloader = new LazyLoadEventgallery({
                    range: 100,
                    elements: 'img.lazyme',
                    image: 'components/com_eventgallery/media/images/blank.gif',
                    onScroll: function () {
                        //console.log('scrolling');
                    },
                    onLoad: function (img) {
                        //console.log('image loaded');
                        setTimeout(function () {
                            img.setStyle('opacity', 0).fade(1);
                        }, 500);
                    },
                    onComplete: function () {
                        //console.log('all images loaded');
                    }

                });

            },
            resizeStart: function () {
                $$('.eventgallery-thumbnails img').setStyle('opacity', 0);


            },
            resizeComplete: function () {
                eventgalleryLazyloader.initialize();
                window.fireEvent('scroll');

            }
        };

        // initialize the imagelist
        eventgalleryImageList = new EventgalleryImagelist(options);

    });

    // add the click event if somebody hits a thumbnail.
    // just open a redirect if the target was not a link
    window.addEvent("domready", function () {
        $$('.thumbnail').addEvent('click', function (e) {
            if ($(e.target).tagName != 'A' && $(e.target).tagName != 'I') {
                document.location.href = $(e.target).getParent('.thumbnail').get('data-url');
            }
        })
    });
</script>


<div id="event">
    <?php IF ($this->params->get('show_date', 1) == 1): ?>
        <h4 class="date">
            <?php echo JHTML::Date($this->folder->getDate()); ?>
        </h4>
    <?php ENDIF ?>
    <h1 class="description">
        <?php echo $this->folder->getDescription(); ?>
    </h1>

    <form method="post" name="adminForm">

        <div class="pagination-limitbox">
            <div class="pull-right limitbox"><?php echo $this->pageNav->getLimitBox(); ?></div>
            <div class="clear"></div>
        </div>

    </form>

    <div class="text">
        <?php echo $this->folder->getText(); ?>
    </div>


    <?php IF ($this->folder->isCartable()  && $this->params->get('use_cart', '1')==1): ?>
        <?php echo $this->loadSnippet('imageset/imagesetselection'); ?>
    <?php ENDIF ?>

    

    <div style="clear:both"></div>

    <div class="eventgallery-thumbnails thumbnails">
        <?php foreach ($this->entries as $entry) : /** @var EventgalleryLibraryFile $entry */ ?>
            <?php $this->assign('entry', $entry) ?>

            <div class="thumbnail" data-url="<?php echo JRoute::_(
                "index.php?option=com_eventgallery&view=singleimage&folder=" . $this->entry->getFolderName() . "&file="
                . $this->entry->getFileName()
            ) ?>">
                <div class="img"
                     title="<?php echo htmlspecialchars($entry->getPlainTextTitle(), ENT_COMPAT, 'UTF-8') ?>">
                    <?php echo $this->entry->getLazyThumbImgTag(50, 50); ?>
                    <?php IF ($this->folder->isCartable()): ?>
                        <a href="#" title="<?php echo JText::_('COM_EVENTGALLERY_CART_ITEM_ADD2CART') ?>"
                           class="button-add2cart eventgallery-add2cart btn btn-primary"
                           data-id="folder=<?php echo $this->entry->getFolderName() . "&file=" . $this->entry->getFileName() ?>"><i
                                class="big"></i></a>
                    <?php ENDIF ?>
                    <?php IF ($this->folder->isCartable() && $this->params->get('show_cart_connector', 0) == 1): ?>
                        <a href="<?php echo EventgalleryHelpersCartconnector::getLink(
                            $this->entry->getFolderName(), $this->entry->getFileName()
                        ); ?>" class="button-cart-connector"
                           title="<?php echo JText::_('COM_EVENTGALLERY_CART_CONNECTOR') ?>"
                           data-folder="<?php echo $this->entry->getFolderName() ?>"
                           data-file="<?php echo $this->entry->getFileName(); ?>"><i class="big"></i></a>
                    <?php ENDIF ?>
                    <?php IF ($this->params->get('use_social_sharing_button', 0)==1 && $this->folder->getAttribs()->get('use_social_sharing', 1)==1):?>
					     <a rel="sharingbutton" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=share&folder='.$this->entry->getFolderName().'&file='.$this->entry->getFileName().'&format=raw'); ?>" class="social-share-button" title="<?php echo JText::_('COM_EVENTGALLERY_SOCIAL_SHARE')?>" ><i class="big"></i></a>
					<?php ENDIF ?>
                </div>
                <div class="details">


                    <div class="content">
                        <?php echo isset($this->entry->hits) ?
                            $this->entry->hits . " " . JText::_('COM_EVENTGALLERY_EVENT_DEFAULT_IMAGE_VIEWS') . " <br>"
                            : ""; ?>
                        <?php IF ($this->entry->allowcomments == 1 && $this->params->get('use_comments') == 1): ?>
                            <?php echo $this->entry->commentCount ?> <?php echo JText::_(
                                'COM_EVENTGALLERY_EVENT_DEFAULT_COMMENT_COMMENTS'
                            ) ?>
                        <?php ENDIF ?>
                    </div>


                </div>
            </div>
        <?php endforeach ?>
        <div style="clear:both"></div>
    </div>
    <form method="post" name="adminForm">

        <div class="pagination">
            <div class="counter pull-right"><?php echo $this->pageNav->getPagesCounter(); ?></div>
            <div class="float_left"><?php echo $this->pageNav->getPagesLinks(); ?></div>
            <div class="clear"></div>
        </div>

    </form>
</div>

