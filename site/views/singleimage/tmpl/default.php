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

include 'components/com_eventgallery/views/social.php';

?>



<script type="text/javascript">
	

	// for auto resize
	var eventgalleryImageList;
	var eventgalleryLazyloader;
	
	window.addEvent("domready", function() {
		var options = {
			rowHeight: 100,
			rowHeightJitter: 0,
			firstImageRowHeight: 1,
			cropLastImage: false,
			eventgallerySelector: '.singleimage',
			eventgalleryImageSelector: '#bigimagelink',
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
			},
			resizeComplete: function() {
				eventgalleryLazyloader.initialize();
				window.fireEvent('scroll');

			}
		};
		
		// initialize the imagelist
 		eventgalleryImageList= new EventgalleryImagelist(options);
		
	});



	<?php IF ($this->model->file->allowcomments==1 && $this->use_comments==1): ?>
		window.addEvent('domready', function() {

			var myVerticalSlide = <?php echo ($this->getErrors())?"new Fx.Slide('commentform').show();":"new Fx.Slide('commentform').hide();"; ?>
			
			$('toggle_comment').addEvent('click', function(e){				
				e.stop();
				myVerticalSlide.toggle();
			});
			
		});
	<?php ENDIF ?>

	
	var myKeyboardEvents = new Keyboard({
	    eventType: 'keydown', 
	    events: { 
	        'left': function(e) {
	        	if (EventGalleryMediabox && EventGalleryMediabox.isActive() 
	        			&& eventGalleryMediaBoxImages && eventGalleryMediaBoxImages[0][2]=='cart') {
	        		return;
	        	}
	        	if ($('prev_image')) {
	        		document.location.href=$('prev_image').get('href'); 
	        	}
	        },
	        'right': function(e) {
	        	if (EventGalleryMediabox && EventGalleryMediabox.isActive() 
	        			&& eventGalleryMediaBoxImages && eventGalleryMediaBoxImages[0][2]=='cart') {
	        		return;
	        	}
	        	if ($('next_image')) {
	        		document.location.href=$('next_image').get('href');
	        	} 	        	
	        }	       
	    }
    });
    
    myKeyboardEvents.activate();
    	

</script>



<div id="singleimage">
	

	<?php IF($this->params->get('show_date',1)==1):?>
		<h4 class="date">
			<?php echo JHTML::date($this->model->folder->date) ?>
		</h4>
	<?php ENDIF ?>
	<h1 class="description">
		<?php echo $this->model->folder->description ?>
	</h1>
	
	<a name="image"></a>

	<div class="btn-group">
		<a class="btn singleimage-overview" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->model->folder->folder."&limitstart=".$this->model->currentLimitStart); ?>" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_OVERVIEW') ?>"><i class="icon-list"></i></a> 
		
		<?php IF ($this->model->firstFile && $this->model->firstFile!=$this->model->file): ?>
			<a class="btn singleimage-first" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=singleimage&folder=".$this->model->firstFile->folder."&file=".$this->model->firstFile->file) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_START') ?>"><i class="icon-fast-backward"></i></a> 
		<?php ENDIF ?>
		
		<?php IF ($this->model->prevFile && $this->model->prevFile!=$this->model->file): ?>
			<a class="btn singleimage-prev" id="prev_image" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=singleimage&folder=".$this->model->prevFile->folder."&file=".$this->model->prevFile->file) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_PREV') ?>"><i class="icon-backward"></i></a> 
		<?php ENDIF ?>
		
		<?php IF ($this->model->nextFile && $this->model->nextFile!=$this->model->file): ?>
			<a class="btn singleimage-next" id="next_image" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=singleimage&folder=".$this->model->nextFile->folder."&file=".$this->model->nextFile->file) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_NEXT') ?>"><i class="icon-forward"></i></a> 
		<?php ENDIF ?>
		
		<?php IF ($this->model->lastFile && $this->model->lastFile!=$this->model->file): ?>
			<a class="btn singleimage-last" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=singleimage&folder=".$this->model->lastFile->folder."&file=".$this->model->lastFile->file) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_END') ?>"><i class="icon-fast-forward"></i></a>
		<?php ENDIF ?>
		
		<?php IF ($this->model->file->allowcomments==1 && $this->use_comments==1): ?>	
			 <a class="btn singleimage-comment" href="#" id="toggle_comment" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_COMMENT') ?>"><i class="icon-comment"></i></a>
		<?php ENDIF ?>
			
		<a class="btn singleimage-zoom" href="<?php echo $this->model->file->getImageUrl(null, null, true) ?>" rel="lightbo2[gallery<?php echo $this->params->get('use_fullscreen_lightbox',0)==1?'fullscreen':''; ?>]" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_ZOOM') ?>"><i class="icon-zoom-in"></i></a>
		

		<?php IF ($this->model->folder->cartable==1):?>
			<a href="#" class="btn button-add2cart eventgallery-add2cart" title="<?php echo JText::_('COM_EVENTGALLERY_CART_ITEM_ADD2CART')?>" data-id="folder=<?php echo $this->model->file->folder."&file=".$this->model->file->file ?>"><i class="icon-cart-small"></i></a>
		<?php ENDIF ?>

		<?php IF ($this->model->folder->cartable==1 && $this->params->get('show_cart_connector', 0)==1):?>
			<a rel="<?php echo $this->params->get('cart_connector_link_rel', 'nofollow')?>" href="<?php echo EventgalleryHelpersCartconnector::getLink($this->model->file->folder, $this->model->file->file); ?>" class="btn button-cart-connector" title="<?php echo JText::_('COM_EVENTGALLERY_CART_CONNECTOR')?>" data-folder="<?php echo $this->model->file->folder ?>" data-file="<?php echo $this->model->file->file; ?>"><i class="icon-cart-connector-small"></i></a>
		<?php ENDIF ?>

		<?php IF ($this->params->get('use_social_sharing_button', 0)==1):?>			
			<a class="btn social-share-button" rel="sharingbutton" href="<?php echo JRoute::_('index.php?option=com_eventgallery&view=singleimage&layout=share&folder='.$this->model->file->folder.'&file='.$this->model->file->file.'&format=raw'); ?>" class="social-share-button" title="<?php echo JText::_('COM_EVENTGALLERY_SOCIAL_SHARE')?>" ><i class="icon-social-share-button-small"></i></a>
		<?php ENDIF ?>

		<?php IF (isset($this->model->file->hits) && $this->params->get('show_imagehits',1)==1): ?>		
			<div  class="btn singleimage-hits"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_HITS') ?> <?php echo $this->model->file->hits?></div>
		<?php ENDIF ?>
	</div>

	<br>
	<?php echo $this->loadTemplate('commentform');?>	
	<br>
	
	<div class="singleimage">
		<a 	class="thumbnail" 
			id="bigimagelink" 			
			data-title="<?php echo rawurlencode($this->model->file->getLightBoxTitle()) ?>"
			href="<?php echo  $this->model->file->getImageUrl(null, null, true) ?>" 
			rel="lightbo2">
			<?php echo $this->model->file->getLazyThumbImgTag(100,100); ?>
		</a>
		<?php IF ($this->model->file->hasTitle()): ?>
		<div class="well description"><?php echo $this->model->file->getTitle(); ?></div>	
		<?php ENDIF ?>
	</div>	
				
	<a name="comments"></a>
	<?php echo $this->loadTemplate('comments');?>		
		
</div>
