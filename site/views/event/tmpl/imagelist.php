<?php 
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php 
	
/*
220x220 220x220 220x220 220x220
300x140 300x140 300x140
300x100 140x100 140x100 300x100
460x200 460x200
940x250

*/	

include 'components/com_eventgallery/views/cart.php';
include 'components/com_eventgallery/views/social.php';

?>


<script type="text/javascript">

	var eventgalleryImageList;
	var eventgalleryLazyloader;
	
	window.addEvent("domready", function() {
		var options = {
			rowHeight: <?php echo $this->params->get('event_image_list_thumbnail_height',150); ?>,
			rowHeightJitter: <?php echo $this->params->get('event_image_list_thumbnail_jitter',50); ?>,
			firstImageRowHeight: <?php echo $this->params->get('event_image_list_thumbnail_first_item_height',2); ?>,
			eventgallerySelector: '.thumbnails',
			eventgalleryImageSelector: '.thumbnails .thumbnail',
			initComplete: function() {
				eventgalleryLazyloader = new LazyLoadEventgallery({ 
				    range: 100, 
				    elements: 'img.lazyme',
				    image: 'components/com_eventgallery/media/images/blank.gif', 
						onScroll: function() { 
							//console.log('scrolling'); 
						},
						onLoad: function(img) { 
							//console.log('image loaded'); 	
							setTimeout(function(){img.setStyle('opacity',0).fade(1);},500); 
						},
						onComplete:function() { 
							//console.log('all images loaded'); 
						}
				    
				});
			},
			resizeStart: function() {
				$$('.thumbnails thumbnail.img').setStyle('opacity',0);
			
			
			},
			resizeComplete: function() {
				eventgalleryLazyloader.initialize();
				window.fireEvent('scroll');
			}
		};
		
		// initialize the imagelist
 		eventgalleryImageList= new EventgalleryImagelist(options);
		
	});
</script>

<div class="event">
	<?php IF($this->params->get('show_date',1)==1):?>
		<h4 class="date">
			<?php echo JHTML::Date($this->folder->date);?>
		</h4>
	<?php ENDIF ?>
	<h1 class="description">
		<?php echo $this->folder->description; ?>
	</h1>
	<div class="text">
		<?php echo $this->folder->text; ?>
	</div>
	
	<div style="clear:both"></div>
	
	<div class="thumbnails">
		<?php foreach($this->entries as $entry) :?>
		    <?php $this->assign('entry',$entry)?>
		    <div class="thumbnail-container">
	    	<a class="thumbnail" href="<?php echo $this->entry->getImageUrl(null, null, true); ?>"
	            title="<?php echo htmlspecialchars($entry->getPlainTextTitle(), ENT_COMPAT, 'UTF-8') ?>"
	            data-title="<?php echo rawurlencode($entry->getLightBoxTitle()) ?>"
	            rel="lightbo2[gallery<?php echo $this->params->get('use_fullscreen_lightbox',0)==1?'fullscreen':''; ?>]"><?php echo $this->entry->getLazyThumbImgTag(50,50);?>
			    </a><?php IF ($this->folder->cartable==1):?><a href="#" title="<?php echo JText::_('COM_EVENTGALLERY_CART_ITEM_ADD2CART')?>" class="button-add2cart eventgallery-add2cart" data-id="folder=<?php echo $this->entry->folder."&file=".$this->entry->file ?>"><i class="big"></i></a><?php ENDIF 
			    ?><?php IF ($this->folder->cartable==1&&$this->params->get('show_cart_connector', 0)==1):?><a rel="<?php echo $this->params->get('cart_connector_link_rel', 'nofollow')?>" href="<?php echo EventgalleryHelpersCartconnector::getLink($this->entry->folder, $this->entry->file); ?>" class="button-cart-connector" title="<?php echo JText::_('COM_EVENTGALLERY_CART_CONNECTOR')?>" data-folder="<?php echo $this->entry->folder ?>" data-file="<?php echo $this->entry->file; ?>"><i class="big"></i></a><?php ENDIF 
			    ?><?php IF ($this->params->get('use_social_sharing_button', 0)==1):?><a rel="sharingbutton" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=share&folder='.$this->entry->folder.'&file='.$this->entry->file.'&format=raw'); ?>" class="social-share-button" title="<?php echo JText::_('COM_EVENTGALLERY_SOCIAL_SHARE')?>" ><i class="big"></i></a><?php ENDIF 
			    ?></div>
		<?php endforeach?>
		<div style="clear: both"></div>
	</div>
</div>
<div style="clear:both"></div>


