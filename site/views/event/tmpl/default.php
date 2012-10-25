<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>


<script type="text/javascript">

	var eventgalleryImageList;
	var lazyloader;
	
	window.addEvent("domready", function() {
		var options = {
			rowHeight: 100,
			rowHeightJitter: 0,
			firstImageRowHeight: 1,
			eventgallerySelector: '.thumbnails',
			eventgalleryImageSelector: '.thumbnail',
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
				$$('.thumbnails img').setStyle('opacity',0);
			
			
			},
			resizeComplete: function() {
				lazyloader.initialize();
				window.fireEvent('scroll');

			}
		};
		
		// initialize the imagelist
 		eventgalleryImageList= new EventgalleryImagelist(options);
		
	});
</script>


<script>
	window.addEvent('domready', function() {
			//create_event_view();
	});

</script>


<div id="event">
	<h4>
		<?php echo JHTML::Date($this->folder->date);?>
	</h4>
	<h1>
		<?php echo $this->folder->description; ?>
	</h1>
	<div>
		<?php echo $this->folder->text; ?>
	</div>
	
	<form method="post" name="adminForm">

		<div class="pagination">
			<div class="float_left"><?php echo $this->pageNav->getPagesCounter(); ?></div>
			<div class="float_left"><?php echo $this->pageNav->getPagesLinks(); ?></div>
			<div class="float_left limitbox"><?php echo $this->pageNav->getLimitBox(); ?></div>
			<div class="clear"></div>
		</div>

	</form>
	
	<div style="clear:both"></div>
		
	<div class="thumbnails">
		<?php foreach($this->entries as $entry) :?>
			    <?php $this->assign('entry',$entry)?>
			    
				<div class="thumbnail" onClick="document.location.href='<?php echo JRoute::_("index.php?view=singleimage&folder=".$this->entry->folder."&file=".$this->entry->file) ?>'">				
					<div class="img"><?php echo $entry->getLazyThumbImgTag(250,250,"",true); ?></div>
					<div class="details">
					
				
			            <div class="content">
			               	<?php echo isset($this->entry->hits)?$this->entry->hits." ".JText::_('COM_EVENTGALLERY_EVENT_DEFAULT_IMAGE_VIEWS')." <br>":""; ?>
							<?php IF ($this->entry->allowcomments==1 && $this->params->get('use_comments')==1): ?>
								<?php echo $this->entry->commentCount ?> <?php echo JText::_('COM_EVENTGALLERY_EVENT_DEFAULT_COMMENT_COMMENTS') ?>
							<?php ENDIF ?>
			            </div>
	
					

					</div>
				</div>		    
		<?php endforeach?>
		<div style="clear:both"></div>
	</div>
	<form method="post" name="adminForm">

	<div class="pagination">
	<div class="float_left"><?php echo $this->pageNav->getPagesCounter(); ?></div>
	<div class="float_left"><?php echo $this->pageNav->getPagesLinks(); ?></div>
	<div class="float_left limitbox"><?php echo $this->pageNav->getLimitBox(); ?></div>
	<div class="clear"></div>
	</div>
		
	</form>
</div>

