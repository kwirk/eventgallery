<div class="item-container item-container-big">
	<div class="item item_first">
		<div class="content">				
			<div class="data">
				<?php IF($this->params->get('show_date',1)==1):?><div class="date"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->getFolderName()) ?>"><?php echo JHTML::Date($this->entry->getDate());?></a></div><?php ENDIF ?>
				<div class="title"><a  href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->getFolderName()) ?>"><?php echo $this->entry->getDescription();?></a></div>
				<?php IF($this->params->get('show_text',1)==1):?><div class="text"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->getFolderName()) ?>"><?php echo $this->entry->getIntroText();?></a></div><?php ENDIF ?>
				<?php IF($this->params->get('show_imagecount',1)==1):?><div class="imagecount"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->getFolderName()) ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_IMAGECOUNT') ?> <?php echo $this->entry->getFileCount();?></a></div><?php ENDIF ?>				
				<?php IF ($this->entry->isCommentingAllowed() && $this->params->get('use_comments')==1 && $this->params->get('show_commentcount',1)==1):?><div class="comment"><a href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->getFolderName()) ?>"><?php echo JText::_('COM_EVENTGALLERY_EVENTS_LABEL_COMMENTCOUNT') ?> <?php echo $this->entry->getCommentCount();?></a></div><?php ENDIF ?>
			</div>
			
			<div class="images event-thumbnails">
				<?php IF ($this->params->get('show_thumbnails',true)):?>
					<?php 
						$files = $this->cache->call( array($this->eventModel, 'getEntries'), $this->entry->getFolderName(), -1, 1, 1);												
					?>
					
					<?php foreach($files as $file):
                        /**
                        * @var EventgalleryLibraryFile $file
                        */?>

						<a class="event-thumbnail" href="<?php echo JRoute::_("index.php?option=com_eventgallery&view=event&folder=".$this->entry->getFolderName()) ?>">
							<?php echo $file->getLazyThumbImgTag(50,50, "", true); ?>	
						</a>											
					<?php ENDFOREACH?>
				<?php ENDIF ?>
				<div style="clear:both"></div>
			</div>
		</div>						
	</div>
</div>