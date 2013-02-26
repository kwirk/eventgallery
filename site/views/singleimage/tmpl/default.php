<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>


<script type="text/javascript">
	

	// for auto resize
	var eventgalleryImageList;
	var lazyloader;
	
	window.addEvent("domready", function() {
		var options = {
			rowHeight: 100,
			rowHeightJitter: 0,
			firstImageRowHeight: 1,
			cropLastImage: false,
			eventgallerySelector: '.singleimage',
			eventgalleryImageSelector: '#bigimagelink',
			initComplete: function() {
				lazyloader = new LazyLoad({ 
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
				lazyloader.initialize();
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
	        	if (Mediabox && Mediabox.isActive() 
	        			&& mediaBoxImages && mediaBoxImages[0][2]=='cart') {
	        		return;
	        	}
	        	if ($('prev_image')) {
	        		document.location.href=$('prev_image').get('href'); 
	        	}
	        },
	        'right': function(e) {
	        	if (Mediabox && Mediabox.isActive() 
	        			&& mediaBoxImages && mediaBoxImages[0][2]=='cart') {
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

<?php include 'components/com_eventgallery/views/cart.php'; ?>

<div id="singleimage">
	

	<h4><?php echo JHTML::date($this->model->folder->date) ?></h4>
	<h1><?php echo $this->model->folder->description ?></h1>
	
	<a name="image"></a>

<div class="btn-group">
	<a class="btn" href="<?php echo JRoute::_("index.php?view=event&folder=".$this->model->folder->folder."&limitstart=".$this->model->currentLimitStart); ?>" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_OVERVIEW') ?>"><i class="icon-list"></i></a> 
	
	<?php IF ($this->model->firstFile): ?>
		<a class="btn" href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->firstFile->folder."&file=".$this->model->firstFile->file) ?>#image" title="title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_START') ?>""><i class="icon-fast-backward"></i></a> 
	<?php ENDIF ?>
	
	<?php IF ($this->model->prevFile): ?>
		<a class="btn" id="prev_image" href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->prevFile->folder."&file=".$this->model->prevFile->file) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_PREV') ?>"><i class="icon-backward"></i></a> 
	<?php ENDIF ?>
	
	<?php IF ($this->model->nextFile): ?>
		<a class="btn" id="next_image" href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->nextFile->folder."&file=".$this->model->nextFile->file) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_NEXT') ?>"><i class="icon-forward"></i></a> 
	<?php ENDIF ?>
	
	<?php IF ($this->model->lastFile): ?>
		<a class="btn" href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->lastFile->folder."&file=".$this->model->lastFile->file) ?>#image" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_END') ?>"><i class="icon-fast-forward"></i></a>
	<?php ENDIF ?>
	
	<?php IF ($this->model->file->allowcomments==1 && $this->use_comments==1): ?>	
		 <a class="btn" href="#" id="toggle_comment" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_COMMENT') ?>"><i class="icon-comment"></i></a>
	<?php ENDIF ?>
		
	<a class="btn" href="<?php echo $this->model->file->getImageUrl(null, null, true) ?>" rel="lightbo2" title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_ZOOM') ?>"><i class="icon-zoom-in"></i></a>

	<a href="#" class="btn button-add2cart eventgallery-add2cart" title="<?php echo JText::_('COM_EVENTGALLERY_CART_ITEM_ADD2CART')?>" data-id="folder=<?php echo $this->model->file->folder."&file=".$this->model->file->file ?>"><i class="icon-cart-small"></i></a>

	<?php IF (isset($this->model->file->hits)): ?>		
		<div  class="btn"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_HITS') ?> <?php echo $this->model->file->hits?></div>
	<?php ENDIF ?>
</div>
	<br>
	<?php echo $this->loadTemplate('commentform');?>	
	<br>
	
	<div class="singleimage">
		<a id="bigimagelink" href="<?php echo  $this->model->file->getImageUrl(null, null, true) ?>" rel="lightbo2">
			<?php echo $this->model->file->getLazyThumbImgTag(100,100); ?>
		</a>		
	</div>	

	
				
	<a name="comments"></a>
	<?php echo $this->loadTemplate('comments');?>		
		
</div>
