<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$cache = & JFactory::getCache();
?>


<div id="events">
	<p class="greetings"><?php echo $this->params->get('greetings'); ?></p>	

	<div>
		<?php $count=0; foreach($this->entries as $entry) :?>
				<?php $this->assign('entry',$entry)?>
				<?php if (0!=$this->params->get('max_events',9999) && $count>=$this->params->get('max_events',9999)) break; ?>
				<?php IF ($count < $this->params->get('max_big_events',9999)): ?>
				
					<a class="item item_first" href="<?php echo JRoute::_("index.php?view=event&folder=".$this->entry->folder) ?>">
						<div class="content">				
							<div class="data">
								<div class="date"><?php echo JHTML::Date($this->entry->date);?></div>
								<div class="title"><?php echo $this->entry->description;?></div>
								<div class="text"><?php echo $this->entry->text;?></div>
								<div class="comment"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $this->entry->overallCount;?></div>					
								<?php IF ($this->params->get('use_comments')==1 && isset($this->entry->commentCount)):?><div class="comment"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_COMMENTCOUNT') ?> <?php echo $this->entry->commentCount;?></div><?php ENDIF ?>
							</div>
							
							<div class="images">

								<?php IF ($this->params->get('show_thumbnails',true)):?>
									<?php 
										$files = $cache->call( array($this->eventModel, 'getEntries'), $entry->folder,1,$this->params->get('max_big_events_thumbnails',1) );
										if (isset($this->entry->titleImage)) {
											array_pop($files);
											array_unshift($files,$this->entry->titleImage);
										}
									
									?>
									
									<?php $first = true;  foreach($files as $file):?>
										<?php 
											if ($first) {
												echo $file->getThumbImgTag($this->params->get('max_big_events_thumbnails_width',306), $this->params->get('max_big_events_thumbnails_width',306),"large"); 
												$first = false;
											} else {
												
												$size = ceil($this->params->get('max_big_events_thumbnails_width',306)/sqrt((count($files)-1)));
												echo $file->getThumbImgTag($size,$size,"small"); 
												
											}
										?>												
									<?php ENDFOREACH?>
								<?php ENDIF ?>
								<div style="clear:both"></div>
							</div>
						</div>						
					</a>
						
					
				<?php ELSE:?>
				    
					<div class="item" onClick="document.location.href='<?php echo JRoute::_("index.php?view=event&folder=".$this->entry->folder) ?>'">
						<div class="content">				
							<div class="date"><?php echo JHTML::Date($this->entry->date)?></div>
							<div class="title"><?php echo $this->entry->description;?></div>
							
							<?php IF ($this->params->get('show_thumbnails',true) && $count>=$this->params->get('max_big_events',9999) && $count <$this->params->get('max_big_events',9999)+$this->params->get('max_middle_events',0)): ?>
								<div class="thumbnails">
									<?php $files = $this->eventModel->getEntries($entry->folder,1,$this->params->get('max_middle_events_thumbnails',0));
										if (isset($this->entry->titleImage)) {
											array_pop($files);
											array_unshift($files,$this->entry->titleImage);
										}
									?>
									<?php foreach($files as $file):?>
									<?php	echo $file->getThumbImgTag($this->params->get('max_middle_events_thumbnails_width',150), $this->params->get('max_middle_events_thumbnails_width',150)); ?>		
									<?php ENDFOREACH?>
								</div>
							<?php ENDIF?>
							
							<div class="comment"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $this->entry->overallCount;?></div>
							<?php IF ($this->params->get('use_comments')==1):?><div class="comment"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_COMMENTCOUNT') ?> <?php echo $this->entry->commentCount;?></div><?php ENDIF ?>
						</div>
						<div class="contentfooter"></div>
					</div>
					
				<?php ENDIF?>
					    
			<?php $count++; endforeach?>
			
			<div style="clear:both"></div>
	</div>
	
	<?php IF ($this->params->get('show_more_link',false)):?>
		<div id="more_pics">
			<a href="<?php echo JRoute::_("index.php?view=events&layout=list")?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_SHOWMORE') ?></a>
		</div>
	<?php ENDIF ?>
</div>		
	
