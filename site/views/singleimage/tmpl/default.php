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
				e = new Event(e);
				e.stop();
				myVerticalSlide.toggle();
			});
			
		});
	<?php ENDIF ?>

	
	var myKeyboardEvents = new Keyboard({
	    eventType: 'keyup', 
	    events: { 
	        'left': function() {if ($('prev_image')) document.location.href=$('prev_image').get('href');},
	        'right': function() {if ($('next_image')) document.location.href=$('next_image').get('href');}
	       
	    }
    });
    
    myKeyboardEvents.activate();
    	

</script>
<div id="singleimage">
	

	<h4><?php echo JHTML::date($this->model->folder->date) ?></h4>
	<h1><?php echo $this->model->folder->description ?></h1>
	
	<?php IF (JRequest::getVar('success')): ?>
		<div class="info">
			<?php IF (JRequest::getVar('success')=='true'): ?>
				<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_SAVE_SUCCESS') ?>
			<?php ELSE: ?>
				<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENT_SAVE_FAILED') ?>
			<?php ENDIF ?>
		</div>
	<?php ENDIF ?>
	<a name="image"></a>

	
	<a href="<?php echo JRoute::_("index.php?view=event&folder=".$this->model->folder->folder."&limitstart=".$this->model->currentLimitStart); ?>"><img title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_OVERVIEW') ?>" src="<?php echo JURI::base().'components/com_eventgallery/media/images/blank.gif'; ?>" class="button_uebersicht"></a> 
	
	<?php IF ($this->model->firstFile): ?>
	<a href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->firstFile->folder."&file=".$this->model->firstFile->file) ?>#image"><img title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_START') ?>" src="<?php echo JURI::base().'components/com_eventgallery/media/images/blank.gif'; ?>" class="button_start"></a> 
	<?php ENDIF ?>
	
	<?php IF ($this->model->prevFile): ?>
	<a id="prev_image" href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->prevFile->folder."&file=".$this->model->prevFile->file) ?>#image"><img title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_PREV') ?>" src="<?php echo JURI::base().'components/com_eventgallery/media/images/blank.gif'; ?>" class="button_zurueck"></a> 
	<?php ENDIF ?>
	
	<?php IF ($this->model->nextFile): ?>
	<a id="next_image" href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->nextFile->folder."&file=".$this->model->nextFile->file) ?>#image"><img title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_NEXT') ?>" src="<?php echo JURI::base().'components/com_eventgallery/media/images/blank.gif'; ?>" class="button_vor"></a> 
	<?php ENDIF ?>
	
	<?php IF ($this->model->lastFile): ?>
	<a href="<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->model->lastFile->folder."&file=".$this->model->lastFile->file) ?>#image"><img title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_END') ?>" src="<?php echo JURI::base().'components/com_eventgallery/media/images/blank.gif'; ?>" class="button_ende"></a>
	<?php ENDIF ?>
	
	<?php IF ($this->model->file->allowcomments==1 && $this->use_comments==1): ?>	
		 <a href="#" id="toggle_comment"><img title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_COMMENT') ?>" src="<?php echo JURI::base().'components/com_eventgallery/media/images/blank.gif'; ?>" class="button_kommentar"></a>
	<?php ENDIF ?>
		
	<a href="<?php echo $this->model->file->getImageUrl(null, null, true) ?>" rel="lightbo2"><img title="<?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_NAV_ZOOM') ?>" src="<?php echo JURI::base().'components/com_eventgallery/media/images/blank.gif'; ?>" class="button_lupe"></a>
	<?php IF (isset($this->model->file->hits)): ?>		
		<div class="hits"><?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_HITS') ?> <?php echo $this->model->file->hits?></div>
	<?php ENDIF ?>
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
