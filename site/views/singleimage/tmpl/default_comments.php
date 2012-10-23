<?php if (count($this->model->comments)>0 && $this->use_comments==1) FOREACH($this->model->comments as $comment): ?>
	<div class="comment">
        <div class="content">
		    <div class="from"><?php echo $comment->name ?> <?php echo JText::_('COM_EVENTGALLERY_SINGLEIMAGE_COMMENTS_WROTE') ?> <?php echo JHTML::date($comment->date) ?>:</div>
			<div class="text"><?php echo $comment->text ?></div>
        </div>
	</div>
<?php ENDFOREACH?>
<div style="clear:both;"></div>