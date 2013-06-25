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
						$files = $this->cache->call( array($this->eventModel, 'getEntries'), $this->entry->folder, -1, 1, 1);
						
						/** for picasa images which have a title image set */
						if (isset($this->entry->titleImage)) {
							array_pop($files);
							array_unshift($files,$this->entry->titleImage);
						}
					?>
					
					<?php foreach($files as $file):?>
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