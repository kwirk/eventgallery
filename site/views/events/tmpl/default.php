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
/**
 * @var JCacheControllerCallback $cache
 */
$cache = JFactory::getCache('com_eventgallery');
?>

<?php include 'components/com_eventgallery/views/cart.php'; ?>

<script type="text/javascript">

	var eventgalleryEventsList;
	var eventgalleryLazyloader;
	
	window.addEvent("domready", function() {

		var options = {
			rowHeightPercentage: 100,
			eventgallerySelector: '.event-thumbnails',
			eventgalleryImageSelector: '.event-thumbnail',
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
				$$('.event-thumbnails .event-thumbnail img').setStyle('opacity',0);
			
			
			},
			resizeComplete: function() {
				eventgalleryLazyloader.initialize();
				window.fireEvent('scroll');
			}
		};
		
		// initialize the imagelist
 		eventgalleryEventsList= new EventgalleryEventsList(options);
		
	});
</script>

<div id="events">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
	<p class="greetings"><?php echo $this->params->get('greetings'); ?></p>	

	<div>
		<?php $count=0; foreach($this->entries as $entry) :?>
				<?php $this->assign('entry',$entry)?>
				<?php if (0!=$this->params->get('max_events',9999) && $count>=$this->params->get('max_events',9999)) break; ?>
				<?php IF ($count < $this->params->get('max_big_events',9999)): ?>
					<div class="item-container item-container-big">
						<div class="item item_first">
							<div class="content">				
								<div class="data">

									<?php IF($this->params->get('show_date',1)==1):?><div class="date"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo JHTML::Date($this->entry->date);?></a></div><?php ENDIF ?>
									<div class="title"><a  href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo $this->entry->description;?></a></div>
									<?php IF($this->params->get('show_text',1)==1):?><div class="text"><a  href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo $this->entry->text;?></a></div><?php ENDIF ?>
									<?php IF($this->params->get('show_imagecount',1)==1):?><div class="imagecount"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $this->entry->overallCount;?></a></div><?php ENDIF ?>				
									<?php IF ($this->params->get('use_comments')==1 && isset($this->entry->commentCount) && $this->params->get('show_commentcount',1)==1):?><div class="comment"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_COMMENTCOUNT') ?> <?php echo $this->entry->commentCount;?></a></div><?php ENDIF ?>
								</div>
								
								<div class="images event-thumbnails">
									<?php IF ($this->params->get('show_thumbnails',true)):?>
										<?php 
											$files = $cache->call( array($this->eventModel, 'getEntries'), $entry->folder, -1, 1, 1);
											if (isset($this->entry->titleImage)) {
												array_pop($files);
												array_unshift($files,$this->entry->titleImage);
											}
										?>
										
										<?php foreach($files as $file): /** @var EventgalleryHelpersImageDefault $file */?>
											<a class="event-thumbnail" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>">
												<?php echo $file->getLazyThumbImgTag(50,50, "", true); ?>	
											</a>											
										<?php ENDFOREACH?>
									<?php ENDIF ?>
									<div style="clear:both"></div>
								</div>
							</div>						
						</div>
					</div>
						
					
				<?php ELSE:?>
					<div class="item-container item-container-middle">    
						<div class="item">
							<div class="content">				
								<?php IF($this->params->get('show_date')==0):?><div class="date"><?php echo JHTML::Date($this->entry->date)?></div><?php ENDIF ?>
								<div class="title"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo $this->entry->description;?></a></div>
								
								<?php IF ($this->params->get('show_thumbnails',true) && $count>=$this->params->get('max_big_events',9999) && $count <$this->params->get('max_big_events',9999)+$this->params->get('max_middle_events',0)): ?>
									<div class="thumbnails">
										<?php $files = $this->eventModel->getEntries($entry->folder,-1,$this->params->get('max_middle_events_thumbnails',0));
											if (isset($this->entry->titleImage)) {
												array_pop($files);
												array_unshift($files,$this->entry->titleImage);
											}
										?>
										<?php foreach($files as $file): /** @var EventgalleryHelpersImageDefault $file */?>
											<a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>">
												<?php	echo $file->getThumbImgTag($this->params->get('max_middle_events_thumbnails_width',150), $this->params->get('max_middle_events_thumbnails_width',150)); ?>		
											</a>
										<?php ENDFOREACH?>
									</div>
								<?php ENDIF?>
								<?php IF($this->params->get('show_imagecount',1)==1):?><div class="imagecount"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $this->entry->overallCount;?></a></div><?php ENDIF ?>				
								<?php IF ($this->params->get('use_comments')==1 && isset($this->entry->commentCount) && $this->params->get('show_commentcount',1)==1):?><div class="comment"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->folder) ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_COMMENTCOUNT') ?> <?php echo $this->entry->commentCount;?></a></div><?php ENDIF ?>
							</div>
							<div class="contentfooter"></div>
						</div>
					</div>
				<?php ENDIF?>
					    
			<?php $count++; endforeach?>
			
			<div style="clear:both"></div>
	</div>
	
	<?php IF ($this->params->get('show_more_link',false)):?>
		<div id="more_pics">
			<a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=events&layout=list")?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_SHOWMORE') ?></a>
		</div>
	<?php ENDIF ?>
</div>		
	
